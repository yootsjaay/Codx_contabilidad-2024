<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use Illuminate\Support\Facades\Storage;
use PhpCfdi\SatWsDescargaMasiva\RequestBuilder\FielRequestBuilder\Fiel;
use PhpCfdi\SatWsDescargaMasiva\RequestBuilder\FielRequestBuilder\FielRequestBuilder;
use PhpCfdi\SatWsDescargaMasiva\Service;
use PhpCfdi\SatWsDescargaMasiva\WebClient\GuzzleWebClient;

class EmpresasController extends Controller
{
    public function index()
    {
        $empresas = Empresa::all();
        return view('empresas.index', compact('empresas'));
    }

    public function create()
    {
        return view('empresas.create');
    }
    public function store(Request $request)
    {
        try {
            // Validar los datos de la solicitud
            $validatedData = $request->validate([
                'nombre' => 'required',
                'razonSocial' => 'required',
                'rfc' => 'required',
                'CURP' => 'required',
                'codigoPostal' => 'required',
                'direccion' => 'required',
                'telefono' => 'required',
                'correo' => 'required|email',
                'logotipo' => 'required|image',
                'archivoKey' => 'required',
                'certificado' => 'required',
                'contraCertificado' => 'required',
            ]);
            
            // Crear una nueva instancia de Empresa
            $empresa = new Empresa();
        
            // Asignar los datos validados
            $empresa->nombre = $validatedData['nombre'];
            $empresa->razonSocial = $validatedData['razonSocial'];
            $empresa->rfc = $validatedData['rfc'];
            $empresa->CURP = $validatedData['CURP'];
            $empresa->codigoPostal = $validatedData['codigoPostal'];
            $empresa->direccion = $validatedData['direccion'];
            $empresa->telefono = $validatedData['telefono'];
            $empresa->correo = $validatedData['correo'];
    
            // Almacenar el logotipo
            if ($request->hasFile('logotipo')) {
                $logotipo = $request->file('logotipo');
                $logotipo->storeAs('logotipos', $logotipo->getClientOriginalName(), 'public');
                $empresa->logotipo = $logotipo->getClientOriginalName();
            }
    
            // Almacenar los archivos con sus nombres originales
            if ($request->hasFile('archivoKey')) {
                $archivoKey = $request->file('archivoKey');
                $archivoKey->storeAs('uploads/archivosKey', $archivoKey->getClientOriginalName(), 'public');
                $empresa->archivoKey = $archivoKey->getClientOriginalName();
            }
            
            if ($request->hasFile('certificado')) {
                $certificado = $request->file('certificado');
                $certificado->storeAs('uploads/certificados', $certificado->getClientOriginalName(), 'public');
                $empresa->certificado = $certificado->getClientOriginalName();
            }
    
            $empresa->contraCertificado = $validatedData['contraCertificado'];
        
            // Guardar la empresa en la base de datos
            $empresa->save();
        
            // Redirigir al usuario a la página de índice de empresas con un mensaje de éxito
            return redirect()->route('empresas.index')->with('success', 'Empresa creada correctamente.');
        } catch (\Exception $e) {
            // En caso de error, volver a la página anterior con un mensaje de error y conservar los datos de la solicitud
            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al crear la empresa: ' . $e->getMessage()]);
        }
    }
    public function show($id)
    {
        $empresa = Empresa::find($id);
        return view('empresas.show', compact('empresa'));
    }

    public function edit($id)
    {
        $empresa = Empresa::find($id);
    
        if (!$empresa) {
            return response()->json(['error' => 'Empresa no encontrada.'], 404);
        }
    
        return view('empresas.edit-form', compact('empresa'));
    }
    
    
    public function update(Request $request, $id)
    {
        $empresa = Empresa::find($id);
        $empresa->nombre = $request->input('nombre');
        // Actualizar los demás campos
        $empresa->logotipo = $request->hasFile('logotipo') ? $request->file('logotipo')->store('logotipos') : $empresa->logotipo;
        $empresa->razonSocial = $request->input('razonSocial');
        $empresa->rfc = $request->input('rfc');
        $empresa->CURP = $request->input('CURP');
        $empresa->codigoPostal = $request->input('codigoPostal');
        $empresa->direccion = $request->input('direccion');
        $empresa->telefono = $request->input('telefono');
        $empresa->correo = $request->input('correo');
        $empresa->archivoKey = $request->hasFile('archivoKey') ? $request->file('archivoKey')->store('archivosKey') : $empresa->archivoKey;
        $empresa->certificado = $request->hasFile('certificado') ? $request->file('certificado')->store('certificados') : $empresa->certificado;
        $empresa->contraCertificado = $request->input('contraCertificado');
        $empresa->save();
    
        return redirect()->route('empresas.index')->with('success', 'Empresa actualizada correctamente.');
    }

    public function destroy($id)
    {
        $empresa = Empresa::find($id);
    
        // Eliminar la empresa de la base de datos
        $empresa->delete();
        

        // Redirigir a la vista de index con un mensaje de éxito
        return redirect()->route('empresas.index')->with('success', 'Empresa eliminada correctamente.');
    }
    
    
}
