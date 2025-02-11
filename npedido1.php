<?php
session_start();
require_once "db.php"; // Conexión segura con MySQLi

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

// Validar si los datos fueron enviados por POST
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["idclientes"], $_POST["idproducto"], $_POST["descripcion"])) {
    header("Location: sistema.php?msg=error_input");
    exit();
}

// Recoger datos y sanitizarlos
$id_cliente = intval($_POST["idclientes"]);
$id_producto = intval($_POST["idproducto"]);
$descripcion = htmlspecialchars($_POST["descripcion"]);
$precio = floatval($_POST["precioventaproducto"]);
$anticipo = floatval($_POST["anticipo"]);
$cantidad = intval($_POST["cantidadproducto"]);
$usuario = $_SESSION["user"];
$fecha_pedido = "{$_POST['apedido']}-{$_POST['mpedido']}-{$_POST['dpedido']}";
$fecha_entrega = "{$_POST['aentrega']}-{$_POST['mentrega']}-{$_POST['dentrega']}";

// Generar un nuevo ID de pedido único
$stmt = $conn->prepare("SELECT MAX(idpedido) FROM pedidos WHERE idcliente = ?");
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$stmt->bind_result($max_id);
$stmt->fetch();
$id_pedido = $max_id ? $max_id + 1 : 101;
$stmt->close();

// Insertar pedido en la base de datos
$stmt = $conn->prepare("
    INSERT INTO pedidos (idpedido, idcliente, fechapedido, fechaentrega, idproducto, descripcion, precio, anticipo, cantidadproducto, estadopedido, usuario)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?)
");
$stmt->bind_param("iisssddiis", $id_pedido, $id_cliente, $fecha_pedido, $fecha_entrega, $id_producto, $descripcion, $precio, $anticipo, $cantidad, $usuario);

if ($stmt->execute()) {
    $stmt->close();
    $redirect_url = ($_POST["ag"] == 2) ? "npedido.php?id=$id_cliente&msg=ok" : "vpedido.php?cri=1";
    header("Location: $redirect_url");
    exit();
} else {
    $stmt->close();
    header("Location: sistema.php?msg=error_db");
    exit();
}
?>
