<?php
session_start();
require_once "db.php";

$fl = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["iddocumento"])) {
    $id_documento = intval($_POST["iddocumento"]);
    $id_cliente = intval($_POST["idcliente"]);
    $fecha = $_POST["year"] . "-" . $_POST["mes"] . "-" . $_POST["dia"];
    $subtotal = floatval($_POST["subtotal"]);
    $iva = $subtotal * 0.13;
    $total = $subtotal + $iva;

    // Obtener datos del cliente
    $stmt = $conn->prepare("SELECT nombrecliente, dircliente FROM clientes WHERE idcliente = ?");
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $cliente = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Guardar documento en la base de datos
    $stmt = $conn->prepare("INSERT INTO cf (iddocumento, fechadocumento, subtotaldocumento, idcliente) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isdi", $id_documento, $fecha, $subtotal, $id_cliente);
    $stmt->execute();
    $stmt->close();

    $fl = 1;
}

include "plus/header.lm";
?>

<script>
function nv(pagina) {
    var int_windowLeft = (screen.width - 600) / 2;
    var int_windowTop = (screen.height - 400) / 2;
    var conca = "docs/" + pagina;
    window.open(conca, 'imfac', 'left=' + int_windowLeft + ',top=' + int_windowTop + ', width=600, height=400,toolbar=0,resizable=0, scrollbars=1');
}
</script>

<body>
<?php include "plus/top.lm"; ?>

<?php if ($fl == 0): ?>
    <form action="cf1.php" method="post">
        <table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
            <tr>
                <td colspan="2" class="titulo"><img src="img/cf.jpg" width="300" height="75" /></td>
            </tr>
            <tr>
                <td><strong>No. de Documento:</strong></td>
                <td>
                    <?php
                    $result = $conn->query("SELECT MAX(iddocumento) AS max_id FROM cf");
                    $row = $result->fetch_assoc();
                    $ndoc = $row["max_id"] ? $row["max_id"] + 1 : 1;
                    ?>
                    <input name="iddocumento" type="text" value="<?php echo $ndoc; ?>" readonly>
                </td>
            </tr>
            <tr>
                <td><b>Nombre del Cliente:</b></td>
                <td>
                    <input name="ncliente" type="text" size="50" value="<?php echo htmlspecialchars($cliente['nombrecliente'] ?? ''); ?>" readonly>
                    <input name="idcliente" type="hidden" value="<?php echo $id_cliente; ?>">
                </td>
            </tr>
            <tr>
                <td><strong>DUI:</strong></td>
                <td><input name="duicliente" type="text" size="15"></td>
            </tr>
            <tr>
                <td><strong>NIT:</strong></td>
                <td><input name="nitcliente" type="text" size="50"></td>
            </tr>
            <tr>
                <td><strong>Fecha:</strong></td>
                <td>
                    <input type="date" name="fecha" value="<?php echo date('Y-m-d'); ?>" required>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table width="100%" border="1" cellpadding="1" cellspacing="1" bordercolor="#164E7F">
                        <tr class="titulo">
                            <td>CANT.</td>
                            <td>Descripción</td>
                            <td>Precio Un.</td>
                            <td>Ventas Gravadas</td>
                        </tr>
                        <?php
                        $total = 0;
                        $anticipos = 0;
                        $nped = 0;
                        for ($u = 1; $u <= $_POST["npedido"]; $u++) {
                            $pedido_key = "pedido$u";
                            if (!isset($_POST[$pedido_key])) continue;

                            $stmt = $conn->prepare("SELECT * FROM pedidos WHERE idpedido = ?");
                            $stmt->bind_param("i", $_POST[$pedido_key]);
                            $stmt->execute();
                            $pedido = $stmt->get_result()->fetch_assoc();
                            $stmt->close();

                            $stmt = $conn->prepare("SELECT nombreproducto FROM productos WHERE idproducto = ?");
                            $stmt->bind_param("i", $pedido["idproducto"]);
                            $stmt->execute();
                            $producto = $stmt->get_result()->fetch_assoc()["nombreproducto"];
                            $stmt->close();

                            $precio = $pedido["cantidadproducto"] * ($pedido["precio"] / 1.13);
                            $total += $precio;
                            $anticipos += $pedido["anticipo"];
                        ?>
                            <tr>
                                <td><input name="cant<?php echo $u; ?>" type="text" size="3" value="<?php echo $pedido["cantidadproducto"]; ?>"></td>
                                <td><textarea name="des<?php echo $u; ?>" cols="80" rows="2"><?php echo htmlspecialchars($producto); ?></textarea></td>
                                <td align="right">$ <input name="pu<?php echo $u; ?>" type="text" size="6" value="<?php echo number_format($pedido["precio"] / 1.13, 2, '.', ''); ?>"></td>
                                <td align="right">$ <input name="pt<?php echo $u; ?>" type="text" size="6" value="<?php echo number_format($precio, 2, '.', ''); ?>"></td>
                            </tr>
                        <?php } ?>
                    </table>
                </td>
            </tr>
            <tr>
                <td>Total:</td>
                <td><input name="total" type="text" size="6" value="<?php echo number_format($total + $iva, 2, '.', ''); ?>" readonly></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="submit" name="Submit" value="Generar"></td>
            </tr>
        </table>
    </form>
<?php else: ?>
    <p>Documento almacenado.</p>
    <input type="button" name="imprimir" value="Imprimir" onclick="nv('<?php echo $ndoc; ?>cf.html');">
<?php endif; ?>

<?php include "plus/bottom.lm"; ?>
</body>
</html>
