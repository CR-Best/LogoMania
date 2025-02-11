<?php
session_start();
require_once "db.php";

$reg = isset($_POST["idclientes"]) ? 1 : 0;
include "plus/header.lm";

if ($reg === 1) {
    $idclientes = intval($_POST["idclientes"]);

    // Consultar pedidos del cliente seleccionado
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM pedidos WHERE idcliente = ?");
    $stmt->bind_param("i", $idclientes);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $t2pedi2 = $result["total"];
    $stmt->close();

    // Consultar datos del cliente
    $stmt = $conn->prepare("SELECT idcliente, nombrecliente FROM clientes WHERE idcliente = ?");
    $stmt->bind_param("i", $idclientes);
    $stmt->execute();
    $cliente = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
?>

<body>
<?php include "plus/top.lm"; ?>

<?php if ($reg === 1): ?>
    <form action="ccf1.php" method="POST">
        <table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
            <tr>
                <td colspan="2" class="titulo">
                    <img src="img/ccf.jpg" width="300" height="75" />
                </td>
            </tr>
            <tr>
                <td width="24%"><b>Nombre del Cliente:</b></td>
                <td width="76%">
                    <input name="ncliente" type="text" size="50" value="<?php echo htmlspecialchars($cliente['nombrecliente']); ?>" readonly>
                    <input name="idcliente" type="hidden" value="<?php echo htmlspecialchars($cliente['idcliente']); ?>">
                    <input name="npedido" type="hidden" value="<?php echo $t2pedi2; ?>">
                </td>
            </tr>
            <tr>
                <td>Pedidos:</td>
                <td>
                    <?php
                    $stmt = $conn->prepare("SELECT idpedido, cantidadproducto, idproducto FROM pedidos WHERE idcliente = ?");
                    $stmt->bind_param("i", $cliente["idcliente"]);
                    $stmt->execute();
                    $pedidos = $stmt->get_result();

                    while ($pedido = $pedidos->fetch_assoc()) {
                        $stmt = $conn->prepare("SELECT nombreproducto FROM productos WHERE idproducto = ?");
                        $stmt->bind_param("i", $pedido["idproducto"]);
                        $stmt->execute();
                        $producto = $stmt->get_result()->fetch_assoc()["nombreproducto"];

                        echo '<input type="checkbox" name="pedido' . $pedido["idpedido"] . '" value="' . $pedido["idpedido"] . '">
                              ' . $pedido["cantidadproducto"] . ' - (' . $pedido["idproducto"] . ') ' . htmlspecialchars($producto) . '<br>';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="Submit" value="Enviar"></td>
            </tr>
        </table>
    </form>

<?php else: ?>
    <form action="ccf.php" method="post">
        <table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
            <tr>
                <td colspan="2" class="titulo">
                    <img src="img/ccf.jpg" width="300" height="75" />
                </td>
            </tr>
            <tr>
                <td width="24%"><b>Nombre del Cliente:</b></td>
                <td width="76%">
                    <select name="idclientes">
                        <?php
                        $stmt = $conn->prepare("SELECT idcliente, nombrecliente FROM clientes WHERE tipodocumento = 1 ORDER BY nombrecliente ASC");
                        $stmt->execute();
                        $clientes = $stmt->get_result();
                        
                        while ($cliente = $clientes->fetch_assoc()) {
                            $stmt = $conn->prepare("SELECT COUNT(*) as total FROM pedidos WHERE idcliente = ?");
                            $stmt->bind_param("i", $cliente["idcliente"]);
                            $stmt->execute();
                            $totalPedidos = $stmt->get_result()->fetch_assoc()["total"];
                            
                            if ($totalPedidos > 0) {
                                echo '<option value="' . htmlspecialchars($cliente["idcliente"]) . '">' . htmlspecialchars($cliente["nombrecliente"]) . '</option>';
                            }
                        }
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
