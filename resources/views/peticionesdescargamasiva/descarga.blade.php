<!-- Modal -->
<div class="modal fade" id="miModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Crear/Descarga masiva</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Inicia el formulario aquí -->
            <form id="tuFormularioID" action="{{ route('peticionesdescargamasiva.store') }}" method="post">
    @csrf <!-- Agrega el token csrf para protección contra CSRF -->
    <div class="modal-body">
        <!-- Select de Empresa -->
        <div class="mb-3">
            <label for="empresa" class="form-label">Empresa</label>
            <!-- Aquí colocas el select para elegir la empresa -->
            <select name="idEmpresa" class="form-control">
                @foreach ($empresas as $empresa)
                    <option value="{{ $empresa->id }}">{{ $empresa->nombre }}</option>
                @endforeach
            </select>
        </div>
      <!-- Input de Fecha de Inicio -->
    <div class="mb-3">
        <label for="fechaInicio" class="form-label">Fecha de Inicio (DD/MM/AA h:m:s)</label>
        <input type="datetime-local" class="form-control" id="fechaInicio" name="fechaInicio">
    </div>
    <!-- Input de Fecha Fin -->
    <div class="mb-3">
        <label for="fechaFin" class="form-label">Fecha Fin (DD/MM/AA h:m:s)</label>
        <input type="datetime-local" class="form-control" id="fechaFin" name="fechaFin">
    </div>

        <!-- Select de Tipo de Peticion -->
        <div class="mb-3">
            <label for="tipoPeticion" class="form-label">Tipo de Peticion</label>
            <select class="form-select" id="tipoPeticion" name="tipoPeticion">
                <option value="emitido">Emitidos</option>
                <option value="recibido">Recibidos</option>
            </select>
        </div>
    </div>
    <!-- Cierra el formulario aquí -->
    <div class="modal-footer">
        <button type="submit" class="btn btn-warning" id="crearPeticionBtn">
            <i class="fas fa-save"></i> Guardar
        </button>
        <button type="button" class="btn btn-dark" data-dismiss="modal">
            <i class="fas fa-times"></i> Cerrar  </button>
    </div>
</form>
</div>
        </div>
    </div>
