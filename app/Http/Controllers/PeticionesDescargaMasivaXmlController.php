<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Empresa;

use App\Models\Peticionesdescargamasiva;
use App\Models\XmlEmitido;
use App\Models\XmlRecibido;
use App\Models\ArchivosZip;
use App\Models\RutasXml;
use ZipArchive;
use App\Models\XmlOrganizado;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use PhpCfdi\SatWsDescargaMasiva\RequestBuilder\FielRequestBuilder\Fiel;
use PhpCfdi\SatWsDescargaMasiva\RequestBuilder\FielRequestBuilder\FielRequestBuilder;
use PhpCfdi\SatWsDescargaMasiva\WebClient\GuzzleWebClient;
use PhpCfdi\SatWsDescargaMasiva\Service;
use PhpCfdi\SatWsDescargaMasiva\Services\Query\QueryParameters;
use PhpCfdi\SatWsDescargaMasiva\Shared\DateTimePeriod;
use Illuminate\Support\Facades\File;
use PhpCfdi\SatWsDescargaMasiva\Shared\RequestType;
use PhpCfdi\SatWsDescargaMasiva\Shared\DownloadType;
use Iluminate\Support\Facades\Cookie;//cockie para almacenar actualizaciones del status
use Illuminate\Support\Facades\Log;
use PhpCfdi\SatWsDescargaMasiva\RequestBuilder\QueryResponseInterface;
use PhpCfdi\SatWsDescargaMasiva\PackageReader\Exceptions\OpenZipFileException;
use PhpCfdi\SatWsDescargaMasiva\PackageReader\CfdiPackageReader;
use PhpCfdi\SatWsDescargaMasiva\PackageReader\MetadataPackageReader;

class PeticionesDescargaMasivaXmlController extends Controller {
        
    public function index()
        {
            // Obtener todas las empresas paginadas
            $empresas = Empresa::paginate(10);
            // Obtener todas las peticiones paginadas
            $peticiones = Peticionesdescargamasiva::count() > 0 ? Peticionesdescargamasiva::paginate(10) : null;
            return view('peticionesdescargamasiva.index', compact('empresas', 'peticiones'));
        }
            //metodo para obtener la ruta del archivo donde se guardo , obteniendo el nombre en la base de datos
            private function getEmpresa(Request $request)
            {
                $empresaId = $request->input('idEmpresa');
                $empresa = Empresa::find($empresaId);
                if (!$empresa) {
                    throw new \Exception('No se encontró la empresa con el ID proporcionado');
                }
               // Obtener la ruta del archivoKey almacenado de forma privada
                $archivoKeyPath = storage_path("app/public/uploads/archivosKey/{$empresa->archivoKey}");
               // Obtener la ruta del certificado almacenado de forma privada
                $certificadoPath = storage_path("app/public/uploads/certificados/{$empresa->certificado}");
                 if (!File::exists($archivoKeyPath) || !File::exists($certificadoPath)) {
                    throw new \Exception('El certificado de la empresa seleccionada no está disponible.');
                }
            
                return [
                    'empresa' => $empresa,
                    'archivoKeyPath' => $archivoKeyPath,
                    'certificadoPath' => $certificadoPath
                ];
            }
            //metodo de creacion del servicio autenticandose al sat 
            private function getFiel($archivoKeyPath, $certificadoPath, $contraCertificado)
            {
            if (!File::exists($archivoKeyPath) || !File::exists($certificadoPath)) {
                throw new \Exception('El certificado de la empresa seleccionada no está disponible.');
            }
            $fiel = Fiel::create(
                file_get_contents($certificadoPath),
                file_get_contents($archivoKeyPath),
                $contraCertificado
            );
            if (!$fiel->isValid()) {
                throw new \Exception('La FIEL no es válida.');
            }
            return $fiel;
        }
          //metodo para obtener el servicio
          private function getService(Fiel $fiel)
          {
              static $service;
              if (!$service) {
                  $webClient = new GuzzleWebClient();
                  $requestBuilder = new FielRequestBuilder($fiel);
                  $service = new Service($requestBuilder, $webClient);
              }
              return $service;
          }

        //metodo para la solicitud de la consulta (con fecha emetidos o recibidos)
        private function getRequestParameters(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'fechaInicio' => 'required|date',
                'fechaFin' => 'required|date',
                'tipoPeticion' => 'required|in:recibido,emitido',
            ]);
        
            if ($validator->fails()) {
                throw new \Exception('Los datos de la solicitud no son válidos: ' . $validator->errors()->first());
            }
        
            $desdeFecha = $request->input('fechaInicio');
            $hastaFecha = $request->input('fechaFin');
            
            if ($request->input('tipoPeticion') === 'recibido') {
                return QueryParameters::create()
                    ->withPeriod(DateTimePeriod::createFromValues($desdeFecha, $hastaFecha))
                    ->withRequestType(RequestType::xml())
                    ->withDownloadType(DownloadType::received());
            } elseif ($request->input('tipoPeticion') === 'emitido') {
                return QueryParameters::create()
                    ->withPeriod(DateTimePeriod::createFromValues($desdeFecha, $hastaFecha))
                    ->withRequestType(RequestType::xml())
                    ->withDownloadType(DownloadType::issued());
            }
        
            throw new \Exception('Tipo de petición inválido. Se esperaba "recibido" o "emitido".');
        }
        
        //metodo para procesar la respuesta de la consulta
       private function processQueryResponse($query, Request $request, Empresa $empresa, $service)
        {
            try {
                // Obtener el identificador de la solicitud
                $requestId = $query->getRequestId();
                
                // Crear la instancia de Peticionesdescargamasiva
                $peticion = new Peticionesdescargamasiva();
                $peticion->idEmpresa = $empresa->id; // campo correcto
                $peticion->nombreEmpresa = $empresa->nombre;
                $peticion->desdeFecha = $request->input('fechaInicio');
                $peticion->hastaFecha = $request->input('fechaFin');
                $peticion->emitidoRecibido = $request->input('tipoPeticion');
                $peticion->uuidPeticion = $requestId; 
                $peticion->nombreArchivo = ''; //  un valor adecuado según  lógica
                $peticion->status = 'pendiente'; //  un valor adecuado según  lógica
        
                // Log para depuración
                Log::debug('Petición preparada: ' . json_encode($peticion));
        
                // Guardar la petición en la base de datos
                if (!$peticion->save()) {
                    throw new \Exception('Error al guardar la petición en la base de datos.');
                }
        
                // Log para confirmar el guardado
                Log::debug('Petición guardada exitosamente.');
        
                // La solicitud fue procesada correctamente
                return response()->json(['success' => true, 'message' => 'Solicitud procesada correctamente'], 200);
            } catch (\Exception $e) {
                // Manejar errores
                Log::error("Ocurrió un error al procesar la solicitud: {$e->getMessage()}");
                return response()->json(['success' => false, 'message' => 'Ocurrió un error al procesar la solicitud'], 500);
            }
        }
        
        public function verificarConsulta(Request $request)
        {
            try {
                $uuidPeticion = $request->input('uuidPeticion'); // el identificador a verificar
                $idEmpresa = $request->input('idEmpresa'); // se selecciona el idEmpresa 
        
                $empresa = Empresa::find($idEmpresa);
                if (!$empresa) {
                    throw new \Exception('No se encontró la empresa correspondiente');
                }
        
                $peticion = Peticionesdescargamasiva::where('uuidPeticion', $uuidPeticion)->first();
                if (!$peticion) {
                    throw new \Exception('No se encontró la petición correspondiente');
                }
                // obtiene los datos de la empresa 
                $empresaData = $this->getEmpresa($request);
                $empresa = $empresaData['empresa'];
                $archivoKeyPath = $empresaData['archivoKeyPath'];
                $certificadoPath = $empresaData['certificadoPath'];
        
                // creacion del servicio, autenticacion
                $fiel = $this->getFiel($archivoKeyPath, $certificadoPath, $empresa->contraCertificado);
                $service = $this->getService($fiel);
                $verify = $service->verify($uuidPeticion);
        
                // Revisar que la consulta haya sido aceptada
                if (!$verify->getStatus()->isAccepted()) {
                    $peticion->status = 'Fallo en la verificación';
                    $peticion->save();
                    return redirect()->route('peticionesdescargamasiva.index')->with('error', "Fallo al verificar la consulta {$uuidPeticion}: {$verify->getStatus()->getMessage()}");
                }
        
                // Revisar que el código de solicitud también haya sido aceptado
                if (!$verify->getCodeRequest()->isAccepted()) {
                    $peticion->status = 'Solicitud rechazada';
                    $peticion->save();
                    return redirect()->route('peticionesdescargamasiva.index')->with('error', "La solicitud {$uuidPeticion} fue rechazada: {$verify->getCodeRequest()->getMessage()}");
                }
                // Verificar el progreso de la generación de los paquetes 
                $statusRequest = $verify->getStatusRequest();
                if ($statusRequest->isExpired() || $statusRequest->isFailure() || $statusRequest->isRejected()) {
                    $peticion->status = 'Estado desconocido';
                    $peticion->save();
                    return redirect()->route('peticionesdescargamasiva.index')->with('error', "La solicitud {$uuidPeticion} no se puede completar");
                }
                // Actualizar el estado de la petición según el progreso de la verificación
                if ($statusRequest->isInProgress() || $statusRequest->isAccepted()) {
                    $peticion->status = 'En proceso';
                } elseif ($statusRequest->isFinished()) {
                    $peticion->status = 'Listo';
                } else {
                    $peticion->status = 'Estado desconocido';
                }
                $peticion->save();
        
                // Después de verificar la consulta
                $this->updatePackageNames($peticion, $verify->getPackagesIds()); // Actualizar los nombres de los paquetes en la petición
                //Descargar los paqutes con los identificadores obtenidos 
                
                // Redirigir según el estado de la petición
                if ($peticion->status === 'Listo') {
                    return redirect()->route('peticionesdescargamasiva.index')->with('success', "La solicitud {$uuidPeticion} está lista");
                } elseif ($peticion->status === 'En proceso') {
                    return redirect()->route('peticionesdescargamasiva.index')->with('success', "La solicitud {$uuidPeticion} se encuentra en proceso");
                } else {
                    return redirect()->route('peticionesdescargamasiva.index')->with('success', 'Paquetes descargados con éxito');
                }
            } catch (\Exception $e) {
                return redirect()->route('peticionesdescargamasiva.index')->with('error', $e->getMessage());
            }
        }
        // Método para guardar los nombres de paquete a descargar
            private function updatePackageNames($peticion, $packageIds)
            {
                foreach ($packageIds as $packageId) {
                    $peticion->nombreArchivo = $packageId;
                $peticion->save();
                }
            }
            
            //Metodo para descargar paquetes XML
            public function descargarPaquetes(Request $request){
            try {
                // Obtener datos de la empresa y la petición
                $uuidPeticion = $request->input('uuidPeticion');
                $idEmpresa = $request->input('idEmpresa');
                $empresa = Empresa::find($idEmpresa);
                $peticion = Peticionesdescargamasiva::where('uuidPeticion', $uuidPeticion)->first();

                if (!$empresa || !$peticion) {
                    throw new \Exception('No se encontró la empresa o la petición correspondiente');
                }

                    $empresaData = $this->getEmpresa($request);
                    $fiel = $this->getFiel($empresaData['archivoKeyPath'], $empresaData['certificadoPath'], $empresa->contraCertificado);
                    $service = $this->getService($fiel);
                    $verify = $service->verify($uuidPeticion);

                    if (!$verify->getStatus()->isAccepted() || !$verify->getCodeRequest()->isAccepted()) {
                        $peticion->status = 'Fallo en la verificación o solicitud rechazada';
                        $peticion->save();
                        return redirect()->route('peticionesdescargamasiva.index')
                                        ->with('error', "Fallo al verificar la consulta {$uuidPeticion}: {$verify->getStatus()->getMessage()}");
                    }

                    $statusRequest = $verify->getStatusRequest();
                    if ($statusRequest->isExpired() || $statusRequest->isFailure() || $statusRequest->isRejected()) {
                        $peticion->status = 'Estado desconocido';
                        $peticion->save();
                        return redirect()->route('peticionesdescargamasiva.index')
                                        ->with('error', "La solicitud {$uuidPeticion} no se puede completar");
                    }

                    $peticion->status = $statusRequest->isInProgress() || $statusRequest->isAccepted() ? 'En proceso' : ($statusRequest->isFinished() ? 'Listo' : 'Estado desconocido');
                    $peticion->save();

                    $this->procesarPaquetes($verify->getPackagesIds(), $service, $idEmpresa);
                    $peticion->status = 'Descargado';
                    $peticion->save();

                    return redirect()->route('peticionesdescargamasiva.index')->with('success', 'Paquetes descargados con éxito');
                } catch (\Exception $e) {
                    return redirect()->route('peticionesdescargamasiva.index')->with('error', $e->getMessage());
                }
            }           
            private function procesarPaquetes($packageIds, $service, $idEmpresa)
            {
                foreach ($packageIds as $packageId) {
                    try {
                        $download = $service->download($packageId);
                        if (!$download->getStatus()->isAccepted()) {
                            throw new \Exception("El paquete {$packageId} no se ha podido descargar: {$download->getStatus()->getMessage()}");
                        }
        
                        $zipfile = storage_path("app/codx_xml/{$packageId}.zip");
                        file_put_contents($zipfile, $download->getPackageContent());
        
                        // Guardar detalles del archivo ZIP en la base de datos
                        $archivoZip = ArchivosZip::create([
                            'nombreArchivo' => "{$packageId}.zip",
                            'rutaArchivo' => $zipfile,
                            'empresa_id' => $idEmpresa,
                           // 'peticion_id' => Peticionesdescargamasiva::where('uuidPeticion', $packageId)->first()->id,
                        ]);
        
                        $peticion = Peticionesdescargamasiva::where('uuidPeticion', $packageId)->first();
                        if ($peticion) {
                            $this->descomprimirYGuardarXML($zipfile, $idEmpresa, $packageId, $peticion->emitidoRecibido, $archivoZip->id);
                        } else {
                            throw new \Exception("No se encontró la petición para el paquete {$packageId}");
                        }
                    } catch (\Exception $e) {
                        \Log::error("Error procesando el paquete {$packageId}: " . $e->getMessage());
                    }
                }
            }
        
            private function descomprimirYGuardarXML($zipfile, $idEmpresa, $packageId, $emitidoRecibido, $zipId)
            {
                try {
                    $cfdiReader = CfdiPackageReader::createFromFile($zipfile);
                    $rutaCarpetaDescomprimida = storage_path('app/codx_xml/' . pathinfo($zipfile, PATHINFO_FILENAME));
        
                    if (!file_exists($rutaCarpetaDescomprimida)) {
                        mkdir($rutaCarpetaDescomprimida, 0777, true);
                        \Log::info("Creada carpeta para descomprimir: {$rutaCarpetaDescomprimida}");
                    }
        
                    foreach ($cfdiReader->cfdis() as $uuid => $content) {
                        $rutaArchivo = "$rutaCarpetaDescomprimida/$uuid.xml";
                        file_put_contents($rutaArchivo, $content);
        
                        // Guardar detalles del archivo XML en la base de datos
                        XmlOrganizado::create([
                            'nombre_archivo' => "$uuid.xml",
                            'ruta_archivo' => $rutaArchivo,
                            'tipo' => $emitidoRecibido,
                            'archivo_zip_id' => $zipId,
                        ]);
        
                        \Log::info("Archivo {$uuid}.xml descomprimido y almacenado en la base de datos");
                    }
                    \Log::info("Descompresión y almacenamiento de archivos XML completada");
                } catch (OpenZipFileException $exception) {
                    \Log::error("Error al descomprimir el archivo ZIP {$packageId}: " . $exception->getMessage());
                } catch (\Exception $e) {
                    \Log::error("Error general al descomprimir y guardar archivos XML: " . $e->getMessage());
                }
            }
            
            
            
            
    //Metodo store para realizar el proceso de autenticacion , consulta , verificacion y descarga de paquetes
        public function store(Request $request)
        {
            try {
                // Obtener empresa y FIEL
                $data = $this->getEmpresa($request);
                $empresa = $data['empresa'];
                $archivoKeyPath = $data['archivoKeyPath'];
                $certificadoPath = $data['certificadoPath'];
                $fiel = $this->getFiel($archivoKeyPath, $certificadoPath, $empresa->contraCertificado);
                // Crear servicio y hacer consulta
                $service = $this->getService($fiel);
                $query = $service->query($this->getRequestParameters($request));
                // Procesar la respuesta de la consulta
               $this->processQueryResponse($query, $request, $empresa, $service);
        
                return redirect()->route('peticionesdescargamasiva.index')->with('success', 'Solicitud procesada correctamente');
            } catch (\Exception $e) {
                return redirect()->route('peticionesdescargamasiva.index')->with('error', $e->getMessage());
            }
        }
            //Metodo para eliminar peticion
            public function destroy($id)
            {
                try {
                    $peticion = Peticionesdescargamasiva::findOrFail($id);
                    $peticion->delete();
                    return redirect()->route('peticionesdescargamasiva.index')->with('success', 'Peticion eliminada correctamente.');
                } catch (\Exception $e) {
                    return redirect()->route('peticionesdescargamasiva.index')->with('error', 'Error al eliminar la petición.');
                }
            }
        }
                


