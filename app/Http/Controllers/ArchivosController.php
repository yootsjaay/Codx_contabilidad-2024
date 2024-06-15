<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Models\ArchivosZip; 
use App\Models\DatosXml;
use App\Models\RutasCarpetas;


class ArchivosController extends Controller
{
    public function index()
    {
       
        return view('factura.index');
    }   

    public function extraerXML(){
       
    }
    
    

}
