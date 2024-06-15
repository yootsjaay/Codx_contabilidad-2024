<div class="mb-3">
    <label for="logotipo" class="form-label">Logotipo (Solo PNG)</label>
    <input type="file" class="form-control" id="logotipo" name="logotipo" accept=".png" onchange="previewImage(event)">
</div>
<!-- Aquí agregamos el elemento img para mostrar la vista previa -->
<img id="logotipo-preview" src="#" alt="Logotipo Preview" style="display: none; max-width: 100%; margin-top: 10px;">

<script>
    function previewImage(event) {
        var input = event.target;
        var preview = document.getElementById('logotipo-preview');
        
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
