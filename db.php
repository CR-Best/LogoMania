<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "sistema";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Activa excepciones para errores

try {
    $conn = new mysqli($host, $user, $pass, $dbname);
    $conn->set_charset("utf8mb4"); // Mejor compatibilidad con caracteres especiales
} catch (mysqli_sql_exception $e) {
    error_log("Error de conexión a la base de datos: " . $e->getMessage());
    die("Error en la conexión a la base de datos. Consulte al administrador.");
}
?>
