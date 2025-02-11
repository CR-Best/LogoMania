<?php
session_start();
require_once "db.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: sistema.php?msg=er");
    exit();
}

$idcliente = intval($_GET["id"]);

// Verificar si el cliente tiene dependencias
$stmt = $conn->prepare("
    SELECT EXISTS(
        SELECT 1 FROM pedidos WHERE idcliente = ? 
        UNION 
        SELECT 1 FROM ccf WHERE idcliente = ? 
        UNION 
        SELECT 1 FROM cf WHERE idcliente = ?
    ) AS has_dependencias
");
$stmt->bind_param("iii", $idcliente, $idcliente, $idcliente);
$stmt->execute();
$has_dependencias = $stmt->get_result()->fetch_assoc()["has_dependencias"];
$stmt->close();

if ($has_dependencias) {
    header("Location: sistema.php?msg=dep_error");
    exit();
}

// Verificar si el cliente existe antes de eliminar
$stmt = $conn->prepare("SELECT idcliente FROM clientes WHERE idcliente = ?");
$stmt->bind_param("i", $idcliente);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    $stmt->close();
    header("Location: sistema.php?msg=not_found");
    exit();
}
$stmt->close();

// Eliminar registros relacionados primero
$conn->begin_transaction();

try {
    $stmt = $conn->prepare("DELETE FROM documentos_ccf WHERE idcliente = ?");
    $stmt->bind_param("i", $idcliente);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM documentos_cf WHERE idcliente = ?");
    $stmt->bind_param("i", $idcliente);
    $stmt->execute();
    $stmt->close();

    // Eliminar el cliente
    $stmt = $conn->prepare("DELETE FROM clientes WHERE idcliente = ?");
    $stmt->bind_param("i", $idcliente);
    $stmt->execute();
    
    if ($stmt->affected_rows === 0) {
        throw new Exception("Error al eliminar el cliente.");
    }

    $stmt->close();
    $conn->commit();
    
    header("Location: sistema.php?msg=cb");
    exit();
} catch (Exception $e) {
    $conn->rollback();
    header("Location: sistema.php?msg=error");
    exit();
}
?>
