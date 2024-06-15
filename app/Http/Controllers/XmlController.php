<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ArchivosZip;
use App\Models\Peticionesdescargamasiva;
use App\Models\DatosXml;
use App\Models\Comprobante;
use App\Models\Emisor;
use App\Models\Receptor;
use App\Models\Concepto;
use App\Models\TimbreFiscalDigital;
use App\Models\Traslado;
use DB;
use XMLReader;
use SimpleXMLElement;

use PhpCfdi\SatWsDescargaMasiva\PackageReader\Exceptions\OpenZipFileException;
use PhpCfdi\SatWsDescargaMasiva\PackageReader\CfdiPackageReader;
use PhpCfdi\SatWsDescargaMasiva\PackageReader\MetadataPackageReader;

class XmlController extends Controller
{
    public function index()
    {
        $archivosZip = ArchivosZip::all();
        return view('listaxml.index', compact('archivosZip'));
    }

    public function create()
    {
        return view('/factura/upload-xml');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'xml_file' => 'required|file|mimes:xml',
        ]);
    
        $xmlFile = $request->file('xml_file');
        $xmlPath = $xmlFile->getPathName();
    
        $reader = new XMLReader();
        $reader->open($xmlPath);
    
        $data = [];
    
        while ($reader->read()) {
            if ($reader->nodeType == XMLReader::ELEMENT && $reader->localName == 'Comprobante') {
                $node = new SimpleXMLElement($reader->readOuterXML());
    
                // Registrar espacios de nombres
                $namespaces = $node->getNamespaces(true);
                foreach ($namespaces as $prefix => $namespace) {
                    $node->registerXPathNamespace($prefix, $namespace);
                }
    
                // Registrar explícitamente el espacio de nombres 'tfd' si no está registrado
                if (!isset($namespaces['tfd'])) {
                    $node->registerXPathNamespace('tfd', 'http://www.sat.gob.mx/TimbreFiscalDigital');
                }
    
                // Extraer datos específicos del XML
                $comprobante = $node;
                $emisor = $comprobante->xpath('//cfdi:Emisor')[0] ?? null;
                $receptor = $comprobante->xpath('//cfdi:Receptor')[0] ?? null;
                $conceptos = $comprobante->xpath('//cfdi:Concepto') ?? [];
                $impuestos = $comprobante->xpath('//cfdi:Impuestos')[0] ?? null;
                $complemento = $comprobante->xpath('//cfdi:Complemento//tfd:TimbreFiscalDigital')[0] ?? null;
    
                // Obtener datos del Comprobante
                $data['version'] = (string) $comprobante['Version'];
                $data['folio'] = (string) $comprobante['Folio'];
                $data['fecha'] = (string) $comprobante['Fecha'];
                $data['sello'] = (string) $comprobante['Sello'];
                $data['formaPago'] = (string) $comprobante['FormaPago'];
                $data['noCertificado'] = (string) $comprobante['NoCertificado'];
                $data['certificado'] = (string) $comprobante['Certificado'];
                $data['subTotal'] = (string) $comprobante['SubTotal'];
                $data['moneda'] = (string) $comprobante['Moneda'];
                $data['total'] = (string) $comprobante['Total'];
                $data['tipoDeComprobante'] = (string) $comprobante['TipoDeComprobante'];
                $data['exportacion'] = (string) $comprobante['Exportacion'];
                $data['metodoPago'] = (string) $comprobante['MetodoPago'];
                $data['lugarExpedicion'] = (string) $comprobante['LugarExpedicion'];
    
                // Obtener datos del Emisor si está presente
                if ($emisor) {
                    $data['rfcEmisor'] = (string) $emisor['Rfc'];
                    $data['nombreEmisor'] = (string) $emisor['Nombre'];
                    $data['regimenFiscalEmisor'] = (string) $emisor['RegimenFiscal'];
                }
    
                // Obtener datos del Receptor si está presente
                if ($receptor) {
                    $data['rfcReceptor'] = (string) $receptor['Rfc'];
                    $data['nombreReceptor'] = (string) $receptor['Nombre'];
                    $data['domicilioFiscalReceptor'] = (string) $receptor['DomicilioFiscalReceptor'];
                    $data['regimenFiscalReceptor'] = (string) $receptor['RegimenFiscalReceptor'];
                    $data['usoCFDI'] = (string) $receptor['UsoCFDI'];
                }
    
                // Obtener datos de los Conceptos
                foreach ($conceptos as $concepto) {
                    $data['conceptos'][] = [
                        'claveProdServ' => (string) $concepto['ClaveProdServ'],
                        'cantidad' => (string) $concepto['Cantidad'],
                        'claveUnidad' => (string) $concepto['ClaveUnidad'],
                        'unidad' => (string) $concepto['Unidad'],
                        'descripcion' => (string) $concepto['Descripcion'],
                        'valorUnitario' => (string) $concepto['ValorUnitario'],
                        'importe' => (string) $concepto['Importe'],
                        'objetoImp' => (string) $concepto['ObjetoImp'],
                    ];
                }
    
                // Obtener datos de los Impuestos si están presentes
                if ($impuestos) {
                    $data['totalImpuestosTrasladados'] = (string) $impuestos['TotalImpuestosTrasladados'];
                    $traslados = $impuestos->xpath('//cfdi:Traslado') ?? [];
                    foreach ($traslados as $traslado) {
                        $data['impuestos'][] = [
                            'base' => (string) $traslado['Base'],
                            'impuesto' => (string) $traslado['Impuesto'],
                            'tipoFactor' => (string) $traslado['TipoFactor'],
                            'tasaOCuota' => (string) $traslado['TasaOCuota'],
                            'importe' => (string) $traslado['Importe'],
                        ];
                    }
                }
    
                // Obtener datos del Timbre Fiscal Digital si está presente
                if ($complemento) {
                    $data['uuid'] = (string) $complemento['UUID'];
                    $data['fechaTimbrado'] = (string) $complemento['FechaTimbrado'];
                    $data['rfcProvCertif'] = (string) $complemento['RfcProvCertif'];
                    $data['selloCFD'] = (string) $complemento['SelloCFD'];
                    $data['noCertificadoSAT'] = (string) $complemento['NoCertificadoSAT'];
                    $data['selloSAT'] = (string) $complemento['SelloSAT'];
                }
    
                break; // Asumimos que sólo hay un Comprobante por archivo
            }
        }
    
        $reader->close();
    
        // Mostrar los datos extraídos
        return view('/factura/xml-data', ['data' => $data]);
    }
    



    //Meetodo para descomprimir archivos zip
    public function descomprimirZip(Request $request)
    {
        // Validar que se haya enviado un nombre de archivo
        $request->validate([
            'nombreArchivo' => 'required|string',
        ]);
        // Obtener el nombre del archivo ZIP del request
        $nombreArchivo = $request->input('nombreArchivo');
        // Buscar el archivo en la base de datos
        $archivo = ArchivosZip::where('nombreArchivo', $nombreArchivo)->first();
    
        if (!$archivo) {
            return "El archivo no existe en la base de datos.";
        }
        // Obtener la ruta del archivo ZIP
        $rutaZIP = storage_path('app/xml_files/' . $archivo->nombreArchivo);
        try {
            // Descomprimir el archivo ZIP y guardar los archivos XML en la carpeta específica
            $cfdiReader = CfdiPackageReader::createFromFile($rutaZIP);
            $rutaCarpetaDescomprimida = storage_path('app/xml_files/' . pathinfo($nombreArchivo, PATHINFO_FILENAME));
            if (!file_exists($rutaCarpetaDescomprimida)) {
                mkdir($rutaCarpetaDescomprimida, 0777, true);
            }
            foreach ($cfdiReader->cfdis() as $uuid => $content) {
                file_put_contents("$rutaCarpetaDescomprimida/$uuid.xml", $content);
            }
            return "Archivos descomprimidos y almacenados en la carpeta $rutaCarpetaDescomprimida";
        } catch (OpenZipFileException $exception) {
            return $exception->getMessage();
        }
    }
}