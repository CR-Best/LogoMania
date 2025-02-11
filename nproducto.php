<?php
session_start();
require_once "db.php"; // Conexión segura con MySQLi

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

// Inicializar variables
$fmod = 0;
$idcliente = isset($_GET["id"]) ? intval($_GET["id"]) : null;

// Eliminar producto
if (isset($_GET["accion"]) && $_GET["accion"] === "eli" && isset($_GET["idproducto"])) {
    $idproducto = intval($_GET["idproducto"]);

    $stmt = $conn->prepare("DELETE FROM productos WHERE idproducto = ?");
    $stmt->bind_param("i", $idproducto);
    if ($stmt->execute()) {
        header("Location: nproducto.php?id=$idcliente&msg=deleted");
    } else {
        header("Location: nproducto.php?id=$idcliente&msg=error");
    }
    exit();
}

// Modificar producto
if (isset($_GET["accion"]) && $_GET["accion"] === "mod" && isset($_GET["idprod"])) {
    $idprod = intval($_GET["idprod"]);
    $stmt = $conn->prepare("SELECT nombreproducto, preciocostoproducto, precioventaproducto FROM productos WHERE idproducto = ?");
    $stmt->bind_param("i", $idprod);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        header("Location: nproducto.php?id=$idcliente");
        exit();
    }

    $producto = $result->fetch_assoc();
    $nombreproducto = $producto["nombreproducto"];
    $preciocostoproducto = $producto["preciocostoproducto"];
    $precioventaproducto = $producto["precioventaproducto"];
    $fmod = 1;
    $stmt->close();
}

// Agregar producto
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["agregar"])) {
    $nombreproducto = htmlspecialchars($_POST["nombreproducto"]);
    $preciocosto = floatval($_POST["preciocosto"]);
    $precioventa = floatval($_POST["precioventa"]);

    // Generar un ID único de producto
    $stmt = $conn->prepare("SELECT MAX(idproducto) FROM productos");
    $stmt->execute();
    $stmt->bind_result($max_id);
    $stmt->fetch();
    $idproducto = $max_id ? $max_id + 1 : 1001;
    $stmt->close();

    // Insertar nuevo producto
    $stmt = $conn->prepare("INSERT INTO productos (idproducto, nombreproducto, preciocostoproducto, precioventaproducto) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isdd", $idproducto, $nombreproducto, $preciocosto, $precioventa);

    if ($stmt->execute()) {
        header("Location: npedido.php?id=$idcliente&idprod=$idproducto&msg=added");
    } else {
        header("Location: nproducto.php?id=$idcliente&msg=error");
    }
    exit();
}

// Modificar producto en base de datos
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["modificar"])) {
    $idproducto = intval($_POST["idproducto"]);
    $nombreproducto = htmlspecialchars($_POST["nombreproducto"]);
    $preciocosto = floatval($_POST["preciocosto"]);
    $precioventa = floatval($_POST["precioventa"]);

    $stmt = $conn->prepare("UPDATE productos SET nombreproducto = ?, preciocostoproducto = ?, precioventaproducto = ? WHERE idproducto = ?");
    $stmt->bind_param("sddi", $nombreproducto, $preciocosto, $precioventa, $idproducto);

    if ($stmt->execute()) {
        header("Location: nproducto.php?id=$idcliente&msg=updated");
    } else {
        header("Location: nproducto.php?id=$idcliente&msg=error");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Productos</title>
    <link rel="stylesheet" href="plus/estilo.css">
    <script>
        function confirmarEliminacion(url) {
            if (confirm("¿Está seguro de que desea eliminar este producto?")) {
                window.location.href = url;
            }
        }
    </script>
</head>
<body>
<form action="nproducto.php" method="post">
    <table width="100%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
        <tr>
            <td colspan="2" class="titulo">SISTEMA DE PRODUCTOS</td>
        </tr>
        <tr>
            <td width="50%">Nombre del Producto:</td>
            <td>
                <input type="text" name="nombreproducto" size="40" value="<?= isset($nombreproducto) ? htmlspecialchars($nombreproducto) : ''; ?>">
                <input type="hidden" name="idcliente" value="<?= htmlspecialchars($idcliente); ?>">
                <br> Código:
                <input type="text" name="idproducto" size="6" value="<?= isset($_GET["idprod"]) ? intval($_GET["idprod"]) : ''; ?>" <?= $fmod ? 'readonly' : ''; ?>>
            </td>
        </tr>
        <tr>
            <td>Precio de Costo:</td>
            <td>$ <input type="text" name="preciocosto" value="<?= isset($preciocostoproducto) ? number_format($preciocostoproducto, 2, '.', '') : ''; ?>"></td>
        </tr>
        <tr>
            <td>Precio de Venta:</td>
            <td>$ <input type="text" name="precioventa" value="<?= isset($precioventaproducto) ? number_format($precioventaproducto, 2, '.', '') : ''; ?>"></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" name="<?= $fmod ? 'modificar' : 'agregar'; ?>" value="<?= $fmod ? 'Modificar' : 'Agregar'; ?>">
                <input type="button" value="Cerrar" onclick="window.close();">
            </td>
        </tr>
        <tr>
            <td colspan="2" class="titulo">PRODUCTOS EXISTENTES</td>
        </tr>
        <tr>
            <td>Producto</td>
            <td>PC</td>
            <td>PV</td>
            <td>Modificar</td>
            <td>Eliminar</td>
        </tr>
        <?php
        $result = $conn->query("SELECT idproducto, nombreproducto, preciocostoproducto, precioventaproducto FROM productos ORDER BY nombreproducto ASC");
        while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row["nombreproducto"]); ?></td>
                <td align="center">$<?= number_format($row["preciocostoproducto"], 2, '.', ''); ?></td>
                <td align="center">$<?= number_format($row["precioventaproducto"], 2, '.', ''); ?></td>
                <td align="center"><a href="nproducto.php?idprod=<?= $row["idproducto"]; ?>&accion=mod&id=<?= $idcliente; ?>"><img src="img/modificar.png" border="0"></a></td>
                <td align="center"><a href="#" onclick="confirmarEliminacion('nproducto.php?idproducto=<?= $row["idproducto"]; ?>&accion=eli&id=<?= $idcliente; ?>')"><img src="img/eliminar.png" border="0"></a></td>
            </tr>
        <?php endwhile; ?>
    </table>
</form>
</body>
</html>
