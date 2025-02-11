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
    SELECT 'pedido' AS source FROM pedidos WHERE idcliente = ?
    UNION
    SELECT 'ccf' FROM ccf WHERE idcliente = ?
    UNION
    SELECT 'cf' FROM cf WHERE idcliente = ?
");
$stmt->bind_param("iii", $idcliente, $idcliente, $idcliente);
$stmt->execute();
$has_dependencias = $stmt->get_result()->num_rows > 0;
$stmt->close();

if ($has_dependencias) {
    header("Location: sistema.php?msg=dep_error");
    exit();
}

// Verificar si el cliente existe
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

// Eliminar registros relacionados antes del cliente
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
$stmt->close();

header("Location: sistema.php?msg=cb");
exit();
?>
