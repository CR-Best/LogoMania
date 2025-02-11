<?php
session_start();
require_once "db.php";
include "plus/header.lm";
?>

<script>
function confirmarEliminacion(url, cliente) {
    if (confirm(`¿Está seguro de que desea eliminar a ${cliente}? Esta acción no se puede deshacer.`)) {
        window.location.href = url;
    }
}
</script>

<body>
<?php include "plus/top.lm"; ?>

<table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
    <tr>
        <td colspan="5" class="titulo">
            <img src="img/bcliente.jpg" width="300" height="75" />
        </td>
    </tr>
    <?php
    $criterio = trim($_POST["busqueda"]);
    $separar = explode(" ", $criterio);
    $busqueda = "%" . implode("%", $separar) . "%";

    $stmt = $conn->prepare("SELECT * FROM clientes WHERE nombrecliente LIKE ?");
    $stmt->bind_param("s", $busqueda);
    $stmt->execute();
    $buscar = $stmt->get_result();
    $total = $buscar->num_rows;
    
    echo "<tr><td colspan=5 class=titulo>Registros encontrados: <b>$total</b></td></tr>";
    ?>
    <tr>
        <td class="titulo"><b>Nombre del Cliente</b></td>
        <td align="center" class="titulo">Modificar</td>
        <td align="center" class="titulo">Historial</td>
        <td align="center" class="titulo">Agregar Pedido</td>
        <td align="center" class="titulo">Eliminar</td>
    </tr>
    <?php while ($res = $buscar->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($res["nombrecliente"]); ?></td>
            <td align="center">
                <a href="modificarcliente.php?id=<?php echo $res["idcliente"]; ?>">
                    <img src="img/modificar.png" border="0" title="Modificar Cliente">
                </a>
            </td>
            <td align="center">
                <a href="historialcliente.php?id=<?php echo $res["idcliente"]; ?>">
                    <img src="img/historial.png" border="0" title="Ver Historial">
                </a>
            </td>
            <td align="center">
                <a href="npedido.php?id=<?php echo $res["idcliente"]; ?>">
                    <img src="img/historial.png" border="0" title="Agregar Pedido">
                </a>
            </td>
            <td align="center">
                <?php
                $stmt_check = $conn->prepare("
                    SELECT 'pedido' AS source FROM pedidos WHERE idcliente = ?
                    UNION
                    SELECT 'ccf' FROM ccf WHERE idcliente = ?
                    UNION
                    SELECT 'cf' FROM cf WHERE idcliente = ?
                ");
                $stmt_check->bind_param("iii", $res["idcliente"], $res["idcliente"], $res["idcliente"]);
                $stmt_check->execute();
                $has_dependencias = $stmt_check->get_result()->num_rows > 0;
                $stmt_check->close();

                if ($has_dependencias):
                ?>
                    <img src="img/eliminar.png" border="0" title="No se puede eliminar este cliente, ya que tiene pedidos o documentos asociados.">
                <?php else: ?>
                    <a href="#" onclick="confirmarEliminacion('eliminarcliente.php?id=<?php echo $res["idcliente"]; ?>', '<?php echo htmlspecialchars($res["nombrecliente"]); ?>')">
                        <img src="img/eliminar.png" border="0" title="Eliminar Cliente">
                    </a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<?php include "plus/bottom.lm"; ?>
</body>
</html>
