<?php 
include("plus/conexion.lm");

// Conexión segura con MySQLi
$stmt = $conectar->prepare("SELECT * FROM ccf");
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo htmlspecialchars($row["nombre_columna"]) . "<br>"; // Reemplaza "nombre_columna" con la columna correcta
}

$stmt->close();
?>
