<!-- resources/views/factura/xml-data.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos del XML</title>
</head>
<body>
    <h1>Datos extraídos del XML</h1>
    <p><strong>Versión:</strong> {{ $data['version'] ?? 'N/A' }}</p>
    <p><strong>Folio:</strong> {{ $data['folio'] ?? 'N/A' }}</p>
    <p><strong>Fecha:</strong> {{ $data['fecha'] ?? 'N/A' }}</p>
    <p><strong>Sello:</strong> {{ $data['sello'] ?? 'N/A' }}</p>
    <p><strong>Forma de Pago:</strong> {{ $data['formaPago'] ?? 'N/A' }}</p>
    <p><strong>No. Certificado:</strong> {{ $data['noCertificado'] ?? 'N/A' }}</p>
    <p><strong>Certificado:</strong> {{ $data['certificado'] ?? 'N/A' }}</p>
    <p><strong>SubTotal:</strong> {{ $data['subTotal'] ?? 'N/A' }}</p>
    <p><strong>Moneda:</strong> {{ $data['moneda'] ?? 'N/A' }}</p>
    <p><strong>Total:</strong> {{ $data['total'] ?? 'N/A' }}</p>
    <p><strong>Tipo de Comprobante:</strong> {{ $data['tipoDeComprobante'] ?? 'N/A' }}</p>
    <p><strong>Exportación:</strong> {{ $data['exportacion'] ?? 'N/A' }}</p>
    <p><strong>Método de Pago:</strong> {{ $data['metodoPago'] ?? 'N/A' }}</p>
    <p><strong>Lugar de Expedición:</strong> {{ $data['lugarExpedicion'] ?? 'N/A' }}</p>

    <h2>Datos del Emisor</h2>
    <p><strong>RFC Emisor:</strong> {{ $data['rfcEmisor'] ?? 'N/A' }}</p>
    <p><strong>Nombre Emisor:</strong> {{ $data['nombreEmisor'] ?? 'N/A' }}</p>
    <p><strong>Régimen Fiscal Emisor:</strong> {{ $data['regimenFiscalEmisor'] ?? 'N/A' }}</p>

    <h2>Datos del Receptor</h2>
    <p><strong>RFC Receptor:</strong> {{ $data['rfcReceptor'] ?? 'N/A' }}</p>
    <p><strong>Nombre Receptor:</strong> {{ $data['nombreReceptor'] ?? 'N/A' }}</p>
    <p><strong>Domicilio Fiscal Receptor:</strong> {{ $data['domicilioFiscalReceptor'] ?? 'N/A' }}</p>
    <p><strong>Régimen Fiscal Receptor:</strong> {{ $data['regimenFiscalReceptor'] ?? 'N/A' }}</p>
    <p><strong>Uso CFDI:</strong> {{ $data['usoCFDI'] ?? 'N/A' }}</p>

    <h2>Conceptos</h2>
    @if (!empty($data['conceptos']))
        @foreach ($data['conceptos'] as $concepto)
            <p><strong>Clave Prod/Serv:</strong> {{ $concepto['claveProdServ'] }}</p>
            <p><strong>Cantidad:</strong> {{ $concepto['cantidad'] }}</p>
            <p><strong>Clave Unidad:</strong> {{ $concepto['claveUnidad'] }}</p>
            <p><strong>Unidad:</strong> {{ $concepto['unidad'] }}</p>
            <p><strong>Descripción:</strong> {{ $concepto['descripcion'] }}</p>
            <p><strong>Valor Unitario:</strong> {{ $concepto['valorUnitario'] }}</p>
            <p><strong>Importe:</strong> {{ $concepto['importe'] }}</p>
            <p><strong>Objeto Imp:</strong> {{ $concepto['objetoImp'] }}</p>
        @endforeach
    @else
        <p>No hay conceptos disponibles</p>
    @endif

    <h2>Impuestos</h2>
    <p><strong>Total Impuestos Trasladados:</strong> {{ $data['totalImpuestosTrasladados'] ?? 'N/A' }}</p>
    @if (!empty($data['impuestos']))
        @foreach ($data['impuestos'] as $impuesto)
            <p><strong>Base:</strong> {{ $impuesto['base'] }}</p>
            <p><strong>Impuesto:</strong> {{ $impuesto['impuesto'] }}</p>
            <p><strong>Tipo Factor:</strong> {{ $impuesto['tipoFactor'] }}</p>
            <p><strong>Tasa o Cuota:</strong> {{ $impuesto['tasaOCuota'] }}</p>
            <p><strong>Importe:</strong> {{ $impuesto['importe'] }}</p>
        @endforeach
    @else
        <p>No hay impuestos disponibles</p>
    @endif

    <h2>Timbre Fiscal Digital</h2>
    <p><strong>UUID:</strong> {{ $data['uuid'] ?? 'N/A' }}</p>
    <p><strong>Fecha Timbrado:</strong> {{ $data['fechaTimbrado'] ?? 'N/A' }}</p>
    <p><strong>RFC Prov. Certif.:</strong> {{ $data['rfcProvCertif'] ?? 'N/A' }}</p>
    <p><strong>Sello CFD:</strong> {{ $data['selloCFD'] ?? 'N/A' }}</p>
    <p><strong>No. Certificado SAT:</strong> {{ $data['noCertificadoSAT'] ?? 'N/A' }}</p>
    <p><strong>Sello SAT:</strong> {{ $data['selloSAT'] ?? 'N/A' }}</p>
</body>
</html>
