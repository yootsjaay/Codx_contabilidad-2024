@extends('adminlte::page')

@section('title', 'Editar Empresa')

@section('content_header')
    <h1>Editar Empresa</h1>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
@stop

@section('content')
    <div class="card">
        <div class="card-body">
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

            <form action="{{ route('empresas.update', $empresa->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                        @include('empresas.edit-general')
                    </div>
                    
                    <div class="tab-pane fade" id="facturacion" role="tabpanel" aria-labelledby="facturacion-tab">
                        @include('empresas.edit-facturacion')
                    </div>
                    <div class="tab-pane fade" id="logotipo" role="tabpanel" aria-labelledby="logotipo-tab">
                        @include('empresas.edit-logo')
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </form>
        </div>
    </div>
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha384-JXfHr5V84D6O8J4MFUO6r0F6X6ec4pqvZ5ZLTTQZ7DAKBaNHSJHlck74KeqUKl3F" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.min.js"></script>
@stop
