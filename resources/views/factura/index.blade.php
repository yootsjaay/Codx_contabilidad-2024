@extends('adminlte::page')

@section('title', 'Almacen')

@section('content_header')
    <h1>Almacen</h1>
@stop

@section('content')
    @if (session('mensaje'))
        <div class="alert alert-success">
            {{ session('mensaje') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form class="row gx-3 gy-2 align-items-center w-100">
                <div class="col-md-3">
                    <label for="clienteEmpresa" class="form-label">Selecciona Cliente Empresa</label>
                    <select class="form-select" id="clienteEmpresa" name="clienteEmpresa">
                        <option selected>Seleccione una opción</option>
                        <option value="1">Cliente 1</option>
                        <option value="2">Cliente 2</option>
                        <option value="3">Cliente 3</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="estatusComprobante" class="form-label">Status</label>
                    <select class="form-select" id="estatusComprobante" name="estatusComprobante">
                        <option selected>Seleccione una opción</option>
                        <option value="1">Vigentes</option>
                        <option value="2">Cancelados</option>
                        <option value="3">Todos</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="fechaInicio" class="form-label">Fecha de Inicio</label>
                    <input type="date" class="form-control" id="fechaInicio" name="fechaInicio">
                </div>
                <div class="col-md-3">
                    <label for="fechaFin" class="form-label">Fecha de Fin</label>
                    <input type="date" class="form-control" id="fechaFin" name="fechaFin">
                </div>
                <div class="col-md-2 align-self-end mt-4">
                    <button type="submit" class="btn btn-warning w-100">Filtrar</button>
                </div>
                <div class="col-md-2 align-self-end mt-4">
                    <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Importar XML
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Importar XML</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="importXmlForm" action="{{ route('upload.xml') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="xmlFiles" class="form-label">Selecciona archivos XML</label>
                                            <input class="form-control" type="file" id="xmlFiles" name="xmlFiles[]" multiple accept=".xml">
                                            <div id="fileList" class="mt-3"></div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <button type="button" class="btn btn-primary" onclick="submitXmlForm()">Guardar cambios</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-responsive mt-4">
                <table id="facturas" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col">Folio</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Nombre Cliente</th>
                            <th scope="col">SubTotal</th>
                            <th scope="col">IVA</th>
                            <th scope="col">Total</th>
                            <th scope="col">Cuenta Abono</th>
                            <th scope="col">Método</th>
                            <th scope="col">Forma</th>
                            <th scope="col">Referencia</th>
                            <th scope="col">Fecha Cobro</th>
                            <th scope="col">Folio REP</th>
                            <th scope="col">Fecha REP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Aquí irían los datos de las facturas -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#facturas').DataTable();
        });

        $('#xmlFiles').on('change', function() {
            var fileList = $('#fileList');
            fileList.empty();

            var files = this.files;
            if (files.length === 0) {
                fileList.append('<p class="file-item">No se seleccionaron archivos.</p>');
            } else {
                for (var i = 0; i < files.length; i++) {
                    fileList.append('<p class="file-item">' + files[i].name + '</p>');
                }
            }
        });

        function submitXmlForm() {
            document.getElementById('importXmlForm').submit();
        }
    </script>
    <style>
        .file-item {
            border: 1px solid #ccc;
            padding: 5px;
            margin: 5px 0;
            border-radius: 4px;
        }
    </style>
@stop
