<?php
session_start();
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["iddocumento"], $_POST["idcliente"], $_POST["subtotal"])) {
    $id_documento = intval($_POST["iddocumento"]);
    $id_cliente = intval($_POST["idcliente"]);
    $fecha = filter_input(INPUT_POST, "fecha", FILTER_SANITIZE_STRING);
    $subtotal = floatval($_POST["subtotal"]);
    $iva = round($subtotal * 0.13, 2);
    $total = round($subtotal + $iva, 2);

    // Obtener datos del cliente
    $stmt = $conn->prepare("SELECT nombrecliente, dircliente FROM clientes WHERE idcliente = ?");
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $cliente = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Guardar documento en la base de datos
    $stmt = $conn->prepare("INSERT INTO ccf (iddocumento, fechadocumento, subtotaldocumento, idcliente) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isdi", $id_documento, $fecha, $subtotal, $id_cliente);
    $stmt->execute();
    $stmt->close();

    header("Location: ccf.php");
    exit();
}

include "plus/header.lm";
?>

<body>
<?php include "plus/top.lm"; ?>

<form action="ccf1.php" method="post">
    <table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
        <tr>
            <td colspan="2" class="titulo"><img src="img/ccf.jpg" width="300" height="75" /></td>
        </tr>
        <tr>
            <td><strong>No. de Documento:</strong></td>
            <td>
                <?php
                $result = $conn->query("SELECT MAX(iddocumento) AS max_id FROM ccf");
                $row = $result->fetch_assoc();
                $ndoc = $row["max_id"] ? $row["max_id"] + 1 : 1;
                ?>
                <input name="iddocumento" type="text" value="<?php echo $ndoc; ?>" readonly>
            </td>
        </tr>
        <tr>
            <td><b>Nombre del Cliente:</b></td>
            <td>
                <?php
                $stmt = $conn->prepare("SELECT idcliente, nombrecliente FROM clientes WHERE idcliente = ?");
                $stmt->bind_param("i", $_POST["idcliente"]);
                $stmt->execute();
                $cliente = $stmt->get_result()->fetch_assoc();
                $stmt->close();
                ?>
                <input name="ncliente" type="text" size="50" value="<?php echo htmlspecialchars($cliente['nombrecliente']); ?>" readonly>
                <input name="idcliente" type="hidden" value="<?php echo $cliente["idcliente"]; ?>">
            </td>
        </tr>
        <tr>
            <td><strong>Registro</strong>:</td>
            <td>
                <?php
                $stmt = $conn->prepare("SELECT registrocliente, girocliente FROM documentos_ccf WHERE idcliente = ?");
                $stmt->bind_param("i", $_POST["idcliente"]);
                $stmt->execute();
                $cli1 = $stmt->get_result()->fetch_assoc();
                $stmt->close();
                ?>
                <input name="registrocliente" type="text" size="15" value="<?php echo htmlspecialchars($cli1["registrocliente"] ?? ''); ?>">
            </td>
        </tr>
        <tr>
            <td><strong>Giro</strong>:</td>
            <td><input name="girocliente" type="text" size="50" value="<?php echo htmlspecialchars($cli1["girocliente"] ?? ''); ?>"></td>
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

                    // Obtener pedidos en una sola consulta
                    $stmt = $conn->prepare("
                        SELECT p.idpedido, p.cantidadproducto, p.precio, pr.nombreproducto 
                        FROM pedidos p
                        JOIN productos pr ON p.idproducto = pr.idproducto
                        WHERE p.idcliente = ?
                    ");
                    $stmt->bind_param("i", $_POST["idcliente"]);
                    $stmt->execute();
                    $pedidos = $stmt->get_result();

                    while ($pedido = $pedidos->fetch_assoc()):
                        $precio = round($pedido["cantidadproducto"] * ($pedido["precio"] / 1.13), 2);
                        $total += $precio;
                    ?>
                        <tr>
                            <td><input name="cant<?php echo $pedido["idpedido"]; ?>" type="text" size="3" value="<?php echo $pedido["cantidadproducto"]; ?>"></td>
                            <td><textarea name="des<?php echo $pedido["idpedido"]; ?>" cols="80" rows="2"><?php echo htmlspecialchars($pedido["nombreproducto"]); ?></textarea></td>
                            <td align="right">$ <input name="pu<?php echo $pedido["idpedido"]; ?>" type="text" size="6" value="<?php echo number_format($pedido["precio"] / 1.13, 2, '.', ''); ?>"></td>
                            <td align="right">$ <input name="pt<?php echo $pedido["idpedido"]; ?>" type="text" size="6" value="<?php echo number_format($precio, 2, '.', ''); ?>"></td>
                        </tr>
                    <?php endwhile;
                    $stmt->close();
                    ?>
                </table>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><input type="submit" name="Submit" value="Generar"></td>
        </tr>
    </table>
</form>

<?php include "plus/bottom.lm"; ?>
</body>
</html>
