<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Fecha Calculada</title>
</head>
<body>

<?php
// Calcular una fecha 32 días atrás usando DateTime
$fecha = new DateTime();
$fecha->modify('-32 days');

// Mostrar la fecha en formato correcto
echo "Fecha hace 32 días: " . $fecha->format("d-m-Y");
?>

</body>
</html>
