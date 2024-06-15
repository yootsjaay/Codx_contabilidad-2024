<div class="modal-body">
    <form action="{{ route('empresas.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $empresa->nombre }}" required>
        </div>
        <div class="mb-3">
            <label for="codigoPostal" class="form-label">Código Postal</label>
            <input type="text" class="form-control" id="codigoPostal" name="codigoPostal" placeholder="Código Postal" value="{{$empresa->codigoPostal}}" required>
        </div>
        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control" id="direccion" name="direccion" value="{{ $empresa->direccion }}" required>
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name="telefono" value="{{ $empresa->telefono }}" required>
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" class="form-control" id="correo" name="correo" value="{{ $empresa->correo }}" required>
        </div>
        
    </form>
</div>
