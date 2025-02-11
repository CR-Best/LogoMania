<?php
session_start();
require_once "db.php";

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

// Sanitizar entrada de usuario
$idcliente = filter_input(INPUT_POST, "idcliente", FILTER_VALIDATE_INT);
$nombrecliente = filter_input(INPUT_POST, "nombrecliente", FILTER_SANITIZE_STRING);
$dircliente = filter_input(INPUT_POST, "dircliente", FILTER_SANITIZE_STRING);
$telcliente = filter_input(INPUT_POST, "telcliente", FILTER_SANITIZE_STRING);
$cellcliente = filter_input(INPUT_POST, "cellcliente", FILTER_SANITIZE_STRING);
$faxcliente = filter_input(INPUT_POST, "faxcliente", FILTER_SANITIZE_STRING);
$emailcliente = filter_input(INPUT_POST, "emailcliente", FILTER_SANITIZE_EMAIL);
$clasecliente = filter_input(INPUT_POST, "clasecliente", FILTER_SANITIZE_STRING);
$tipodocumento = filter_input(INPUT_POST, "tipodocumento", FILTER_VALIDATE_INT);
$registrocliente = filter_input(INPUT_POST, "registrocliente", FILTER_SANITIZE_STRING);
$girocliente = filter_input(INPUT_POST, "girocliente", FILTER_SANITIZE_STRING);

if (!$idcliente || !$tipodocumento) {
    header("Location: sistema.php?msg=er");
    exit();
}

// Iniciar transacción
$conn->begin_transaction();

try {
    // Actualizar cliente
    $stmt = $conn->prepare("
        UPDATE clientes 
        SET nombrecliente=?, dircliente=?, telcliente=?, cellcliente=?, faxcliente=?, emailcliente=?, clasecliente=?, tipodocumento=?
        WHERE idcliente=?
    ");
    $stmt->bind_param("ssssssssi", $nombrecliente, $dircliente, $telcliente, $cellcliente, $faxcliente, $emailcliente, $clasecliente, $tipodocumento, $idcliente);
    $stmt->execute();
    $stmt->close();

    // Determinar la tabla de documentos
    $tabla = ($tipodocumento == 1) ? "documentos_ccf" : "documentos_cf";
    $campo1 = ($tipodocumento == 1) ? "registrocliente" : "duicliente";
    $campo2 = ($tipodocumento == 1) ? "girocliente" : "nitcliente";

    // Verificar si el cliente ya tiene un registro en documentos
    $stmt = $conn->prepare("SELECT idcliente FROM $tabla WHERE idcliente = ?");
    $stmt->bind_param("i", $idcliente);
    $stmt->execute();
    $result = $stmt->get_result();
    $existe = $result->num_rows > 0;
    $stmt->close();

    if ($existe) {
        // Actualizar documento
        $stmt = $conn->prepare("UPDATE $tabla SET $campo1=?, $campo2=? WHERE idcliente=?");
        $stmt->bind_param("ssi", $registrocliente, $girocliente, $idcliente);
    } else {
        // Insertar nuevo documento
        $stmt = $conn->prepare("INSERT INTO $tabla (idcliente, $campo1, $campo2) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $idcliente, $registrocliente, $girocliente);
    }
    $stmt->execute();
    $stmt->close();

    // Confirmar transacción
    $conn->commit();

    header("Location: sistema.php?msg=cac");
    exit();
} catch (Exception $e) {
    $conn->rollback();
    header("Location: sistema.php?msg=er");
    exit();
}
?>
