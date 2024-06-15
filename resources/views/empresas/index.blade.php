@extends('adminlte::page')

@section('title', 'CoDx Empresas')

@section('content_header')
    <h1>Clientes</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <div class="d-flex justify-content-center">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    + Registrar Cliente
                </button>
                
            </div>
            <table id="empresas" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Logotipo</th>
                        <th scope="col">Razon Social</th>
                        <th scope="col">RFC</th>
                        <th scope="col">Direccion</th>
                        <th scope="col">Telefono</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($empresas as $empresa)
                    <tr>
                        <th scope="row">{{ $empresa->id }}</th>
                        <td>{{ $empresa->nombre }}</td>
                        <td><img src="{{ asset('storage/logotipos/' . $empresa->logotipo) }}" alt="Logotipo" style="max-width: 100px; max-height: 100px;"></td>
                        <td>{{ $empresa->razonSocial }}</td>
                        <td>{{ $empresa->rfc }}</td>
                        <td>{{ $empresa->direccion }}</td>
                        <td>{{ $empresa->telefono }}</td>
                        <td>{{ $empresa->correo }}</td>
                        <td>
                            <div class="d-flex">
                                <a href="{{ route('empresas.edit', $empresa->id) }}" class="btn btn-warning btn-sm mr-2">
                                    Editar
                                </a>
                                <form action="{{ route('empresas.destroy', $empresa->id) }}" class="formulario-eliminar" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-dark btn-sm">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>  
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Crear Empresa</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('empresas.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">Información General</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="facturacion-tab" data-bs-toggle="tab" data-bs-target="#facturacion" type="button" role="tab" aria-controls="facturacion" aria-selected="false">Información de Facturación</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="logotipo-tab" data-bs-toggle="tab" data-bs-target="#logotipo" type="button" role="tab" aria-controls="logotipo" aria-selected="false">Logotipo</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                            @include('empresas.general')
                        </div>
                        <div class="tab-pane fade" id="facturacion" role="tabpanel" aria-labelledby="facturacion-tab">
                            @include('empresas.facturacion')
                        </div>
                        <div class="tab-pane fade" id="logotipo" role="tabpanel" aria-labelledby="logotipo-tab">
                            @include('empresas.logotipo')
                        </div>
                    </div>
                    <div class="modal-footer">
        <button type="button" class="btn btn-dark mt-3" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-warning mt-3">Registrar Cliente</button>
      </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>
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
            $('#empresas').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/2.0.2/i18n/es-ES.json"
                }
            });

            @if(session('success') === 'Empresa eliminada correctamente.')
                Swal.fire({
                    title: "Eliminado!",
                    text: "La Empresa Se ha Eliminado Correctamente.",
                    icon: "success"
                });
            @endif

            $('.formulario-eliminar').submit(function (e) {
                e.preventDefault();

                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "Esta Empresa se eliminará definitivamente.",
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
       @if(session('success') === 'Empresa creada correctamente.')
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