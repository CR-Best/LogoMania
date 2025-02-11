<?php 
include("plus/conexion.lm");

if (isset($_GET["idpedido"]) && isset($_GET["idcliente"]) && isset($_GET["ac"])) {
    $idpedido = intval($_GET["idpedido"]);
    $idcliente = intval($_GET["idcliente"]);
    $estado = intval($_GET["ac"]);

    // Actualizar estado del pedido de manera segura
    $stmt = $conectar->prepare("UPDATE pedidos SET estadopedido=? WHERE idpedido=? AND idcliente=?");
    $stmt->bind_param("iii", $estado, $idpedido, $idcliente);
    $stmt->execute();
    $stmt->close();
}

// Eliminar pedidos antiguos (más de 7 días)
$fecha_limite = date("Y-m-d", strtotime("-7 days"));
$stmt = $conectar->prepare("DELETE FROM pedidos WHERE fechaentrega < ?");
$stmt->bind_param("s", $fecha_limite);
$stmt->execute();
$stmt->close();

include("plus/header.lm");
include("plus/top.lm");
?>

Ver: 
<a href="vpedido.php?cri=1">Pendientes</a> | 
<a href="vpedido.php?cri=2">En Proceso</a> | 
<a href="vpedido.php?cri=3">Finalizados</a> | 
<a href="vpedido.php">Todos</a>

<table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
    <tr>
        <td class="titulo"><img src="img/bcliente.jpg" width="300" height="75" /></td>
    </tr>

    <?php
    // Filtrar por estado si se proporciona
    $sql = "SELECT * FROM pedidos";
    if (isset($_GET["cri"]) && in_array($_GET["cri"], ["1", "2", "3"])) {
        $sql .= " WHERE estadopedido = ?";
    }
    $sql .= " ORDER BY fechaentrega ASC";

    $stmt = $conectar->prepare($sql);
    if (isset($_GET["cri"])) {
        $stmt->bind_param("i", $_GET["cri"]);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<tr><td class='titulo'>Registros encontrados: <b>" . $result->num_rows . "</b></td></tr>";

    while ($res = $result->fetch_assoc()) {
        // Obtener nombre del producto
        $stmt_prod = $conectar->prepare("SELECT nombreproducto FROM productos WHERE idproducto = ?");
        $stmt_prod->bind_param("i", $res["idproducto"]);
        $stmt_prod->execute();
        $producto = $stmt_prod->get_result()->fetch_assoc()["nombreproducto"] ?? "Desconocido";
        $stmt_prod->close();

        // Obtener nombre del cliente
        $stmt_clie = $conectar->prepare("SELECT nombrecliente FROM clientes WHERE idcliente = ?");
        $stmt_clie->bind_param("i", $res["idcliente"]);
        $stmt_clie->execute();
        $nombrecliente = $stmt_clie->get_result()->fetch_assoc()["nombrecliente"] ?? "Desconocido";
        $stmt_clie->close();

        // Determinar imagen de estado
        $imagen = match ($res["estadopedido"]) {
            2 => "proceso",
            3 => "terminado",
            default => "pendiente",
        };

        // Formato de fechas
        $fecha_pedido = date("Y/m/d", strtotime($res["fechapedido"]));
        $fecha_entrega = date("Y/m/d", strtotime($res["fechaentrega"]));

        echo "<tr>
                <td>
                    <img src='img/$imagen.jpg' align='right' width='100' height='80'>
                    <b>Fecha de pedido:</b> $fecha_pedido - 
                    <b>Fecha de Entrega:</b> $fecha_entrega<br>
                    <b>Detalle:</b> " . htmlspecialchars($res["cantidadproducto"]) . " - (" . htmlspecialchars($res["idproducto"]) . ") " . htmlspecialchars($producto) . "<br>
                    <b>Precio:</b> $ " . number_format($res["precio"], 2, '.', '') . "<br>
                    <b>Descripción:</b> " . htmlspecialchars($res["descripcion"]) . "<br>
                    <b>Cliente:</b> " . htmlspecialchars($nombrecliente) . "<br><br>
                    <b><a href='mpedido.php?idpedido=" . $res["idpedido"] . "'>Modificar este pedido</a></b><br><br>
                    <b>Cambiar estado de pedido:</b> 
                    <a href='vpedido.php?idpedido=" . $res["idpedido"] . "&idcliente=" . $res["idcliente"] . "&ac=1'>Pendiente</a> &int;
                    <a href='vpedido.php?idpedido=" . $res["idpedido"] . "&idcliente=" . $res["idcliente"] . "&ac=2'>En Proceso</a> &int;
                    <a href='vpedido.php?idpedido=" . $res["idpedido"] . "&idcliente=" . $res["idcliente"] . "&ac=3'>Terminado</a>
                </td>
            </tr>";
    }
    ?>
</table>

<?php 
include("plus/bottom.lm");
?>
