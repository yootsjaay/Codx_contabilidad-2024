@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Prueba descarga masiva xml</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
    <h1>Resultado de la consulta</h1> 
    <div class="table-responsive">
        <table id="tabla-archivos" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Uuid</th>
                    <th scope="col">Status</th>
                    <th scope="col">Acción</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                 
              
                    </td>
                 <td>   </td>
                     
                     
                    </td>
                    @if (isset($error))
                            <div style="color: red;">{{ $error }}</div>
                        @endif 
                        @if (isset($mensaje))
                            <div style="color: green;">{{ $mensaje }}</div>
                        @endif
                    <td>
                  
                    <td>    
                        <a href="{{route('descargar-archivo')}}" class="btn btn-primary">Descargar</a>
                    </td>

                </tr>
            </tbody>
        </table>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.bootstrap5.css">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.js"></script>
    <script src="//cdn.datatables.net/plug-ins/2.0.2/i18n/es-ES.json"></script>
    <script>
        $(document).ready(function() {
            $('#tabla-archivos').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/2.0.2/i18n/es-ES.json"
                }
            });
        });
    </script>
@stop
