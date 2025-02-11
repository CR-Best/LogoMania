<?php
session_start();
require_once "db.php";

$reg = isset($_POST["idclientes"]) ? 1 : 0;
include "plus/header.lm";

if ($reg === 1) {
    // Consultar datos del cliente
    $idclientes = intval($_POST["idclientes"]);
    $stmt = $conn->prepare("SELECT idcliente, nombrecliente FROM clientes WHERE idcliente = ?");
    $stmt->bind_param("i", $idclientes);
    $stmt->execute();
    $cliente = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Consultar pedidos del cliente y unir con los productos en una sola consulta
    $stmt = $conn->prepare("
        SELECT p.idpedido, p.cantidadproducto, pr.idproducto, pr.nombreproducto 
        FROM pedidos p
        JOIN productos pr ON p.idproducto = pr.idproducto
        WHERE p.idcliente = ?
    ");
    $stmt->bind_param("i", $idclientes);
    $stmt->execute();
    $pedidos = $stmt->get_result();
}
?>

<body>
<?php include "plus/top.lm"; ?>

<?php if ($reg === 1): ?>
    <form action="cf1.php" method="POST">
        <table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
            <tr>
                <td colspan="2" class="titulo">
                    <img src="img/cf.jpg" width="300" height="75" />
                </td>
            </tr>
            <tr>
                <td width="24%"><b>Nombre del Cliente:</b></td>
                <td width="76%">
                    <input name="ncliente" type="text" size="50" value="<?php echo htmlspecialchars($cliente['nombrecliente']); ?>" readonly>
                    <input name="idcliente" type="hidden" value="<?php echo htmlspecialchars($cliente['idcliente']); ?>">
                </td>
            </tr>
            <tr>
                <td>Pedidos:</td>
                <td>
                    <?php while ($pedido = $pedidos->fetch_assoc()): ?>
                        <input type="checkbox" name="pedido<?php echo $pedido["idpedido"]; ?>" value="<?php echo $pedido["idpedido"]; ?>">
                        <?php echo htmlspecialchars($pedido["cantidadproducto"] . " - (" . $pedido["idproducto"] . ") " . $pedido["nombreproducto"]); ?><br>
                    <?php endwhile; ?>
                </td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="Submit" value="Enviar"></td>
            </tr>
        </table>
    </form>
    <?php $stmt->close(); ?>

<?php else: ?>
    <form action="cf.php" method="post">
        <table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
            <tr>
                <td colspan="2" class="titulo">
                    <img src="img/cf.jpg" width="300" height="75" />
                </td>
            </tr>
            <tr>
                <td width="24%"><b>Nombre del Cliente:</b></td>
                <td width="76%">
                    <select name="idclientes">
                        <?php
                        $stmt = $conn->prepare("SELECT idcliente, nombrecliente FROM clientes WHERE tipodocumento = 2 ORDER BY nombrecliente ASC");
                        $stmt->execute();
                        $clientes = $stmt->get_result();
                        
                        while ($cliente = $clientes->fetch_assoc()) {
                            echo '<option value="' . htmlspecialchars($cliente["idcliente"]) . '">' . htmlspecialchars($cliente["nombrecliente"]) . '</option>';
                        }
                        $stmt->close();
                        ?>
                    </select>
                    <input name="envio" type="submit" value="Enviar">
                </td>
            </tr>
        </table>
    </form>
<?php endif; ?>

<?php include "plus/bottom.lm"; ?>
</body>
</html>
