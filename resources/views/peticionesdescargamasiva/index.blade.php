@extends('adminlte::page')
@section('title', 'PeticionesDescargaMasivaXml')
@section('content_header')
    <h1>PeticionesDescargaMasivaXml</h1>
@stop
@section('content')
    <p>PeticionesDescargaMasivaXml</p>
    <div class="card">
        <div class="card-body">
        <div class="d-flex justify-content-center">
        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#miModal">
    + Peticiones DescargaMasivaXml
</button>
</div>
<!-- Modal -->
<div class="table-responsive">
    <table id="descargas" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Empresa</th>
                <th scope="col">Desde Fecha</th>
                <th scope="col">Hasta Fecha</th>
                <th scope="col">Tipo Petición</th>
                <th scope="col">Uuid Petición</th>
                <th scope="col">Fecha Creación</th>
                <th scope="col">Nombre Archivo</th>
                <th scope="col">Verificar</th>
                <th scope="col">Status</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @if($peticiones)
                @foreach ($peticiones as $peticion)
                    <tr id="peticion-{{$peticion->id}}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $peticion->empresa->nombre }}</td>
                        <td>{{ $peticion->desdeFecha }}</td>
                        <td>{{ $peticion->hastaFecha }}</td>
                        <td>{{ $peticion->emitidoRecibido }}</td>
                        <td>{{ $peticion->uuidPeticion }}</td>
                        <td>{{ $peticion->created_at }}</td>
                        <td>{{ $peticion->nombreArchivo }}</td>
                        <td>
                            <form method="POST" action="{{ route('verificarConsulta') }}">
                                @csrf
                                <input type="hidden" name="uuidPeticion" value="{{ $peticion->uuidPeticion }}">
                                <input type="hidden" name="idEmpresa" value="{{ $peticion->empresa->id }}">
                                <button type="submit" class="btn btn-sm btn-dark">Verificar</button>
                            </form>
                        </td>
                        <td>
                            @if ($peticion->status === 'En proceso')
                                <span class="badge badge-warning text-dark">En proceso</span>
                            @elseif ($peticion->status === 'Listo')
                                <span class="badge badge-success">Listo</span>
                            @elseif ($peticion->status === 'Descargado')
                                <span class="badge badge-warning">Descargado</span>
                            @elseif ($peticion->status === 'Estado desconocido')
                                <span class="badge badge-warning text-dark">Aceptada</span>
                            @else
                                <span class="badge badge-warning text-dark">Aceptada en proceso</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Botones de acción">
                                <form method="GET" action="{{ route('descargarPaquetes') }}">
                                    @csrf
                                    <input type="hidden" name="uuidPeticion" value="{{ $peticion->uuidPeticion }}">
                                    <input type="hidden" name="idEmpresa" value="{{ $peticion->empresa->id }}">
                                    <button type="submit" class="btn btn-sm btn-warning">Descargar</button>
                                </form>
                                <form action="{{ route('peticionesdescargamasiva.destroy', $peticion->id) }}" class="boton-eliminar" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>

  @include('peticionesdescargamasiva.descarga')

  @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-warning">
        {{ session('error') }}
    </div>
@endif

@if(session('estadoPeticionId'))
    <div class="alert alert-info">
        Estado de la petición: {{ session('estadoPeticionId') }}
    </div>
@endif

@if(session('verificar'))
    @if(!$verificar->getStatus()->isAccepted())
        <div class="alert alert-warning">
            Fallo al verificar la consulta: {{ $verificar->getStatus()->getMessage() }}
        </div>
    @elseif(!$verificar->getCodeRequest()->isAccepted())
        <div class="alert alert-danger">
            La solicitud fue rechazada: {{ $verificar->getCodeRequest()->getMessage() }}
        </div>
    @else
        <div class="alert alert-warning">
            La solicitud fue procesada correctamente
        </div>
    @endif
@endif


@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@stop

@section('js')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
         $(document).ready(function() {
            $('#descargas').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/2.0.2/i18n/es-ES.json"
                }
            });
            @if(session('success') === 'Peticion eliminada correctamente.')
                Swal.fire({
                    title: "Eliminado!",
                    text: " Se ha Eliminado Correctamente.",
                    icon: "success"
                });
            @endif

            $('.boton-eliminar').submit(function (e) {
                e.preventDefault();

                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "Esta Peticion se eliminará definitivamente.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, Eliminar",
                    cancelButtonText: "Cancelar",
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Envía el formulario si el usuario confirma la eliminación
                        this.submit();
                    }
                });
            });
        }); 
        </script>
    <script>
       @if(session('success') === 'Solicitud procesada correctamente')
        // NOTIFICACION DE ALERT 
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 1000
            });
        @endif
    </script>   

    
    
@stop
