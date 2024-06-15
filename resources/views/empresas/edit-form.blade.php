<!-- resources/views/empresas/edit-form.blade.php -->
<form action="{{ route('empresas.update', $empresa->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
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
            @include('empresas.edit-general', ['empresa' => $empresa])
        </div>
        <div class="tab-pane fade" id="facturacion" role="tabpanel" aria-labelledby="facturacion-tab">
            @include('empresas.edit-facturacion', ['empresa' => $empresa])
        </div>
        <div class="tab-pane fade" id="logotipo" role="tabpanel" aria-labelledby="logotipo-tab">
            @include('empresas.edit-logo', ['empresa' => $empresa])
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-dark mt-3" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-warning mt-3">Guardar Cambios</button>
    </div>
</form>
