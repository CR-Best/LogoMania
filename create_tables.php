<?php
require_once "db.php";

$queries = [
    "CREATE TABLE IF NOT EXISTS actividades (
        id INT AUTO_INCREMENT PRIMARY KEY,
        idusuario VARCHAR(25) NOT NULL,
        tiempo DATE NOT NULL,
        hora TIME NOT NULL,
        actividad VARCHAR(255) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;",

    "CREATE TABLE IF NOT EXISTS clientes (
        idcliente INT AUTO_INCREMENT PRIMARY KEY,
        nombrecliente VARCHAR(255) NOT NULL,
        dircliente TEXT NOT NULL,
        telcliente VARCHAR(15),
        cellcliente VARCHAR(15),
        emailcliente VARCHAR(100),
        tipodocumento INT(1) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;",

    "CREATE TABLE IF NOT EXISTS users (
        idusuario INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(50) NOT NULL,
        password VARCHAR(255) NOT NULL,
        nivel CHAR(1) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];

foreach ($queries as $query) {
    if ($conn->query($query) === TRUE) {
        echo "Tabla creada correctamente.<br>";
    } else {
        echo "Error creando tabla: " . $conn->error . "<br>";
    }
}

// Insertar usuario de prueba con contraseña segura
$password_hash = password_hash("admin", PASSWORD_DEFAULT);
$conn->query("INSERT INTO users (nombre, password, nivel) VALUES ('Carlos Alberto Rosales', '$password_hash', '1')");

$conn->close();
?>
