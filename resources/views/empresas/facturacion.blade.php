<div class="mb-3">
    <label for="razonSocial" class="form-label">Razón Social</label>
    <div class="input-group">
        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
        <input type="text" class="form-control" id="razonSocial" name="razonSocial" placeholder="Ingrese la razón social" required>
    </div>
</div>

<div class="mb-3">
    <div class="input-group">
        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
        <input type="text" class="form-control" id="rfc" name="rfc" placeholder="Ingrese el RFC (solo se admite hasta 13 caracteres)" maxlength="13" required>
    </div>
</div>

<div class="mb-3">
    <div class="input-group">
        <span class="input-group-text"><i class="fas fa-address-card"></i></span>
        <input type="text" class="form-control" id="CURP" name="CURP" placeholder="Ingrese el CURP (solo se admite hasta 18 caracteres)" maxlength="18" required>
    </div>
</div>

<div class="mb-3">
    <label for="archivoKey" class="form-label">Archivo Key (.key)</label>
    <input type="file" class="form-control" id="archivoKey" name="archivoKey">
</div>

<div class="mb-3">
    <label for="certificado" class="form-label">Certificado (.cer)</label>
    <input type="file" class="form-control" id="certificado" name="certificado">
</div>

<div class="mb-3">
    <label for="contraCertificado" class="form-label">Contraseña del Certificado</label>
    <input type="password" class="form-control" id="contraCertificado" name="contraCertificado" minlength="8" required>
</div>
