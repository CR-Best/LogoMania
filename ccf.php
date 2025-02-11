<?php
session_start();
require_once "db.php";

$reg = isset($_POST["idclientes"]) ? 1 : 0;
include "plus/header.lm";

if ($reg === 1) {
    $idclientes = intval($_POST["idclientes"]);

    // Consultar datos del cliente y el número de pedidos en una sola consulta
    $stmt = $conn->prepare("
        SELECT c.idcliente, c.nombrecliente, COUNT(p.idpedido) AS total_pedidos 
        FROM clientes c
        LEFT JOIN pedidos p ON c.idcliente = p.idcliente
        WHERE c.idcliente = ?
    ");
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
                    <input name="npedido" type="hidden" value="<?php echo $cliente['total_pedidos']; ?>">
                </td>
            </tr>
            <tr>
                <td>Pedidos:</td>
                <td>
                    <?php
                    $stmt = $conn->prepare("
                        SELECT p.idpedido, p.cantidadproducto, pr.idproducto, pr.nombreproducto 
                        FROM pedidos p
                        JOIN productos pr ON p.idproducto = pr.idproducto
                        WHERE p.idcliente = ?
                    ");
                    $stmt->bind_param("i", $cliente["idcliente"]);
                    $stmt->execute();
                    $pedidos = $stmt->get_result();

                    while ($pedido = $pedidos->fetch_assoc()) {
                        echo '<input type="checkbox" name="pedido' . $pedido["idpedido"] . '" value="' . $pedido["idpedido"] . '">
                              ' . htmlspecialchars($pedido["cantidadproducto"]) . ' - (' . htmlspecialchars($pedido["idproducto"]) . ') ' . htmlspecialchars($pedido["nombreproducto"]) . '<br>';
                    }
                    $stmt->close();
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
                        // Obtener clientes con pedidos en una sola consulta
                        $stmt = $conn->prepare("
                            SELECT c.idcliente, c.nombrecliente 
                            FROM clientes c
                            WHERE c.tipodocumento = 1 
                            AND EXISTS (SELECT 1 FROM pedidos p WHERE p.idcliente = c.idcliente)
                            ORDER BY c.nombrecliente ASC
                        ");
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
