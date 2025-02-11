<?php
session_start();
require_once "db.php";

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

// Validar y sanitizar entrada GET
if (!isset($_GET["idpedido"]) || !is_numeric($_GET["idpedido"])) {
    header("Location: vpedido.php");
    exit();
}

$idpedido = intval($_GET["idpedido"]);

// Obtener datos del pedido
$stmt = $conn->prepare("SELECT * FROM pedidos WHERE idpedido = ?");
$stmt->bind_param("i", $idpedido);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: vpedido.php");
    exit();
}

$pedido = $result->fetch_assoc();
$stmt->close();

// Extraer datos del pedido
$idcliente = $pedido["idcliente"];
$fechaentrega = explode("-", $pedido["fechaentrega"]);
$descripcion = htmlspecialchars($pedido["descripcion"]);
$precio = number_format($pedido["precio"], 2, '.', '');
$anticipo = number_format($pedido["anticipo"], 2, '.', '');
$cantidadproducto = $pedido["cantidadproducto"];

include "plus/header.lm";
?>

<script>
function calcularTotal() {
    let cantidad = parseFloat(document.pedidos.cantidadproducto.value) || 0;
    let precio = parseFloat(document.pedidos.precio.value) || 0;
    let anticipoAnterior = parseFloat("<?php echo $anticipo; ?>") || 0;
    let total = cantidad * precio;
    
    document.pedidos.total.value = total.toFixed(2);
    document.pedidos.anticipo.value = Math.max((total / 2) - anticipoAnterior, 0).toFixed(2);
}
</script>

<body onload="calcularTotal();">
<?php include "plus/top.lm"; ?>

<form action="mpedido.php" method="post" name="pedidos">
    <table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
        <tr>
            <td colspan="2" class="titulo">
                <img src="img/npedido.jpg" width="300" height="75" />
            </td>
        </tr>
        <tr>
            <td><b>Nombre del Cliente:</b></td>
            <td>
                <?php
                $stmt = $conn->prepare("SELECT idcliente, nombrecliente FROM clientes WHERE idcliente = ?");
                $stmt->bind_param("i", $idcliente);
                $stmt->execute();
                $cliente = $stmt->get_result()->fetch_assoc();
                ?>
                <input type="hidden" name="idpedido" value="<?php echo $idpedido; ?>">
                <input type="text" name="nombre" size="50" value="<?php echo htmlspecialchars($cliente["nombrecliente"]); ?>" readonly>
                <input type="hidden" name="idcliente" value="<?php echo $cliente["idcliente"]; ?>">
            </td>
        </tr>
        <tr>
            <td><b>Producto:</b></td>
            <td>
                <?php
                $stmt = $conn->prepare("SELECT idproducto, nombreproducto FROM productos WHERE idproducto = ?");
                $stmt->bind_param("i", $pedido["idproducto"]);
                $stmt->execute();
                $producto = $stmt->get_result()->fetch_assoc();
                ?>
                <input type="text" name="producto" size="50" value="<?php echo htmlspecialchars($producto["nombreproducto"]); ?>" readonly>
                <input type="hidden" name="idproducto" value="<?php echo $producto["idproducto"]; ?>">
            </td>
        </tr>
        <tr>
            <td><b>Cantidad:</b></td>
            <td><input type="number" name="cantidadproducto" value="<?php echo $cantidadproducto; ?>" onchange="calcularTotal();"></td>
        </tr>
        <tr>
            <td><b>Precio Unitario:</b></td>
            <td>$ <input type="text" name="precio" value="<?php echo $precio; ?>" onchange="calcularTotal();"></td>
        </tr>
        <tr>
            <td><b>Anticipo:</b></td>
            <td>$ <input type="text" name="anticipo" value="<?php echo $anticipo; ?>" readonly></td>
        </tr>
        <tr>
            <td><b>Descripción:</b></td>
            <td><textarea name="descripcion" cols="50" rows="3"><?php echo $descripcion; ?></textarea></td>
        </tr>
        <tr>
            <td><b>Fecha de Entrega:</b></td>
            <td>
                <select name="dentrega">
                    <?php for ($i = 1; $i <= 31; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo ($fechaentrega[2] == $i) ? "selected" : ""; ?>><?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
                -
                <select name="mentrega">
                    <?php
                    $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
                    for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo ($fechaentrega[1] == $i) ? "selected" : ""; ?>><?php echo $meses[$i - 1]; ?></option>
                    <?php endfor; ?>
                </select>
                -
                <select name="aentrega">
                    <?php for ($a = date("Y"); $a <= date("Y") + 1; $a++): ?>
                        <option value="<?php echo $a; ?>" <?php echo ($fechaentrega[0] == $a) ? "selected" : ""; ?>><?php echo $a; ?></option>
                    <?php endfor; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><b>Total:</b></td>
            <td>$ <input type="text" name="total" readonly></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" name="enviar" value="Procesar">
                <input type="reset" value="Restablecer">
                <input type="button" value="Cancelar" onclick="window.location='vpedido.php';">
            </td>
        </tr>
    </table>
</form>

<?php include "plus/bottom.lm"; ?>
</body>
</html>
