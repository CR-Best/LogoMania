<?php
session_start();
require_once "db.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: bcliente.php");
    exit();
}

$idcliente = intval($_GET["id"]);

// Obtener datos del cliente
$stmt = $conn->prepare("SELECT * FROM clientes WHERE idcliente = ?");
$stmt->bind_param("i", $idcliente);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: bcliente.php");
    exit();
}

$datos = $result->fetch_assoc();
$stmt->close();

include "plus/header.lm";
?>

<body>
<?php include "plus/top.lm"; ?>

<table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
    <tr>
        <td colspan="2" class="titulo">
            <strong>Historial de <?php echo htmlspecialchars($datos["nombrecliente"]); ?></strong>
            <br><br>
        </td>
    </tr>

    <!-- Pedidos Actuales -->
    <tr>
        <td colspan="2" class="titulo">Pedidos Actuales</td>
    </tr>
    <?php
    $stmt = $conn->prepare("
        SELECT p.*, pr.nombreproducto 
        FROM pedidos p 
        JOIN productos pr ON p.idproducto = pr.idproducto 
        WHERE p.idcliente = ?
    ");
    $stmt->bind_param("i", $idcliente);
    $stmt->execute();
    $pedidos = $stmt->get_result();

    if ($pedidos->num_rows > 0):
        while ($res = $pedidos->fetch_assoc()):
            $imagen = "pendiente";
            if ($res["estadopedido"] == 2) $imagen = "proceso";
            if ($res["estadopedido"] == 3) $imagen = "terminado";

            $fecha_entrega = date("Y/m/d", strtotime($res["fechaentrega"]));
            $fecha_pedido = date("Y/m/d", strtotime($res["fechapedido"]));
    ?>
        <tr>
            <td colspan="2">
                <img src="img/<?php echo $imagen; ?>.jpg" align="right" width="100" height="80">
                <b>Fecha de pedido:</b> <?php echo $fecha_pedido; ?> - 
                <b>Fecha de Entrega:</b> <?php echo $fecha_entrega; ?><br>
                <b>Detalle:</b> <?php echo htmlspecialchars($res["cantidadproducto"]); ?> - 
                (<?php echo htmlspecialchars($res["idproducto"]); ?>) <?php echo htmlspecialchars($res["nombreproducto"]); ?><br>
                <b>Precio:</b> $<?php echo number_format($res["precio"], 2, '.', ''); ?><br>
                <b>Descripción:</b> <?php echo htmlspecialchars($res["descripcion"]); ?><br><br>
                <hr>
            </td>
        </tr>
    <?php
        endwhile;
    else:
    ?>
        <tr>
            <td colspan="2">Actualmente no hay pedidos para este cliente.</td>
        </tr>
    <?php
    endif;
    $stmt->close();
    ?>

    <!-- Documentos Emitidos -->
    <tr>
        <td colspan="2" class="titulo">Documentos emitidos</td>
    </tr>
    <?php
    $tabla_documento = ($datos["tipodocumento"] == 2) ? "cf" : "ccf";

    $stmt = $conn->prepare("SELECT * FROM $tabla_documento WHERE idcliente = ?");
    $stmt->bind_param("i", $idcliente);
    $stmt->execute();
    $documentos = $stmt->get_result();

    if ($documentos->num_rows > 0):
        $o = 0;
        while ($res = $documentos->fetch_assoc()):
            $o++;
            $fecha_doc = date("Y/m/d", strtotime($res["fechadocumento"]));
    ?>
        <tr>
            <td colspan="2">
                <?php echo $o; ?>- Documento #<?php echo $res["iddocumento"]; ?>. Emitido en: <?php echo $fecha_doc; ?>
                <?php
                // Verificar si el documento está anulado
                $stmt_anulado = $conn->prepare("SELECT * FROM documentos_anulados WHERE iddocumento = ?");
                $stmt_anulado->bind_param("i", $res["iddocumento"]);
                $stmt_anulado->execute();
                if ($stmt_anulado->get_result()->num_rows > 0) {
                    echo " (ANULADO)";
                }
                $stmt_anulado->close();
                ?>
                <br><b>Detalle:</b><br><?php echo nl2br(htmlspecialchars($res["detalledocumento"])); ?>
                <br><hr>
            </td>
        </tr>
    <?php
        endwhile;
    else:
    ?>
        <tr>
            <td colspan="2">Actualmente no hay documentos emitidos para este cliente.</td>
        </tr>
    <?php
    endif;
    $stmt->close();
    ?>
</table>

<?php include "plus/bottom.lm"; ?>
</body>
</html>
