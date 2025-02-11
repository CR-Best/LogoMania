<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Fecha Calculada</title>
</head>
<body>

<?php
// Definir zona horaria
date_default_timezone_set("America/Mexico_City"); // Ajusta según tu país

// Obtener número de días (permite personalización con GET, con valor por defecto 32)
$dias_atras = isset($_GET["dias"]) ? (int) $_GET["dias"] : 32;

// Calcular fecha usando DateTime
$fecha = new DateTime();
$fecha->modify("-$dias_atras days");

// Mostrar la fecha en formato correcto
echo "Fecha hace $dias_atras días: <strong>" . htmlspecialchars($fecha->format("d-m-Y")) . "</strong>";
?>

</body>
</html>
