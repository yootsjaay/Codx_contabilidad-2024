<div class="mb-3">
    <label for="razonSocial" class="form-label">Razón Social</label>
    <div class="input-group">
        <input type="text" class="form-control" id="razonSocial" name="razonSocial" placeholder="Ingrese la razón social" value="{{$empresa->razonSocial}}" required>
    </div>
</div>

<div class="mb-3">
    <label for="rfc" class="form-label">RFC</label>
    <div class="input-group">
        <input type="text" class="form-control" id="rfc" name="rfc" placeholder="Ingrese el RFC" values ="{{$empresa->rfc}}" required>
    </div>
</div>

<div class="mb-3">
    <label for="CURP" class="form-label">CURP</label>
    <div class="input-group">
        <input type="text" class="form-control" id="CURP" name="CURP" placeholder="Ingrese el CURP" value="{{ $empresa->CURP }}" required>
    </div>
</div>

<div class="mb-3">
    <label for="archivoKey" class="form-label">Archivo Key (.key)</label>
    <input type="file" class="form-control" id="archivoKey" name="archivoKey" value="{{$empresa->archivoKey}}"required>
</div>

<div class="mb-3">
    <label for="certificado" class="form-label">Certificado (.cer)</label>
    <input type="file" class="form-control" id="certificado" name="certificado" value="{{$empresa->certificado}}"required>
</div>

<div class="mb-3">
    <label for="contraCertificado" class="form-label">Contraseña del Certificado</label>
    <input type="password" class="form-control" id="contraCertificado" name="contraCertificado" value="{{ $empresa->contraCertificado }}" required>
</div>
