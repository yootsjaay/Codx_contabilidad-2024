<!-- resources/views/upload-xml.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Archivo XML</title>
</head>
<body>
    <form action="/factura/upload-xml" method="post" enctype="multipart/form-data">
        @csrf
        <label for="xml_file">Selecciona un archivo XML:</label>
        <input type="file" name="xml_file" id="xml_file" required>
        <button type="submit">Subir</button>
    </form>
</body>
</html>
