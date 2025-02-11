<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "sistema";

// Conectar con MySQLi
$conn = new mysqli($host, $user, $pass, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

// Configurar charset UTF-8
$conn->set_charset("utf8");
?>
