<?php
session_start();
require_once "db.php";

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

// Validar y sanitizar datos del formulario
$nombrecliente = trim($_POST["nombrecliente"] ?? '');
$dircliente = trim($_POST["dircliente"] ?? '');
$telcliente = trim($_POST["telcliente"] ?? '');
$cellcliente = trim($_POST["cellcliente"] ?? '');
$faxcliente = trim($_POST["faxcliente"] ?? '');
$emailcliente = trim($_POST["emailcliente"] ?? '');
$clasecliente = $_POST["clasecliente"] ?? 'A';
$tipodocumento = intval($_POST["tipodocumento"] ?? 2);
$registrocliente = trim($_POST["registrocliente"] ?? '');
$girocliente = trim($_POST["girocliente"] ?? '');

// Validar que los campos requeridos no estén vacíos
if (empty($nombrecliente) || empty($dircliente)) {
    header("Location: sistema.php?msg=er");
    exit();
}

// Validar correo electrónico
if (!empty($emailcliente) && !filter_var($emailcliente, FILTER_VALIDATE_EMAIL)) {
    header("Location: sistema.php?msg=invalid_email");
    exit();
}

// Iniciar una transacción para asegurar la integridad de los datos
$conn->begin_transaction();

try {
    // Insertar el cliente (idcliente será AUTO_INCREMENT en la base de datos)
    $stmt = $conn->prepare("INSERT INTO clientes (nombrecliente, dircliente, telcliente, cellcliente, faxcliente, emailcliente, clasecliente, tipodocumento) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssi", $nombrecliente, $dircliente, $telcliente, $cellcliente, $faxcliente, $emailcliente, $clasecliente, $tipodocumento);
    $stmt->execute();
    $idcliente = $stmt->insert_id; // Obtener el ID del cliente insertado
    $stmt->close();

    // Insertar documentos según el tipo de consumidor
    if ($tipodocumento == 1) {
        $stmt = $conn->prepare("INSERT INTO documentos_ccf (idcliente, registrocliente, girocliente) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $idcliente, $registrocliente, $girocliente);
    } else {
        $stmt = $conn->prepare("INSERT INTO documentos_cf (idcliente, registrocliente, girocliente) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $idcliente, $registrocliente, $girocliente);
    }
    $stmt->execute();
    $stmt->close();

    // Confirmar la transacción
    $conn->commit();

    // Redirigir con mensaje de éxito
    header("Location: sistema.php?msg=ca");
    exit();
} catch (Exception $e) {
    // Si hay un error, revertir la transacción
    $conn->rollback();
    header("Location: sistema.php?msg=er");
    exit();
}
?>
