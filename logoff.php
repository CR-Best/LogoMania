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
            <strong>Historial de <?php echo htmlspecialchars($datos["nombrecliente"], ENT_QUOTES, 'UTF-8'); ?></strong>
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
            $imagen = match ($res["estadopedido"]) {
                2 => "proceso",
                3 => "terminado",
                default => "pendiente",
            };
            ?>
            <tr>
                <td colspan="2">
                    <img src="img/<?php echo $imagen; ?>.jpg" align="right" width="100" height="80">
                    <b>Fecha de pedido:</b> <?php echo date("Y/m/d", strtotime($res["fechapedido"])); ?> - 
                    <b>Fecha de Entrega:</b> <?php echo date("Y/m/d", strtotime($res["fechaentrega"])); ?><br>
                    <b>Detalle:</b> <?php echo intval($res["cantidadproducto"]); ?> - 
                    (<?php echo htmlspecialchars($res["idproducto"], ENT_QUOTES, 'UTF-8'); ?>) <?php echo htmlspecialchars($res["nombreproducto"], ENT_QUOTES, 'UTF-8'); ?><br>
                    <b>Precio:</b> $<?php echo number_format($res["precio"], 2, '.', ''); ?><br>
                    <b>Descripción:</b> <?php echo nl2br(htmlspecialchars($res["descripcion"], ENT_QUOTES, 'UTF-8')); ?><br><br>
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
    // Determinar la tabla de documentos de manera segura
    $tabla_documento = match ($datos["tipodocumento"]) {
        1 => "ccf",
        2 => "cf",
        default => null,
    };

    if ($tabla_documento) {
        $stmt = $conn->prepare("
            SELECT d.*, da.iddocumento AS anulado
            FROM $tabla_documento d
            LEFT JOIN documentos_anulados da ON d.iddocumento = da.iddocumento
            WHERE d.idcliente = ?
        ");
        $stmt->bind_param("i", $idcliente);
        $stmt->execute();
        $documentos = $stmt->get_result();

        if ($documentos->num_rows > 0):
            $o = 0;
            while ($res = $documentos->fetch_assoc()):
                $o++;
                ?>
                <tr>
                    <td colspan="2">
                        <?php echo $o; ?>- Documento #<?php echo intval($res["iddocumento"]); ?>. Emitido en: <?php echo date("Y/m/d", strtotime($res["fechadocumento"])); ?>
                        <?php if ($res["anulado"]): ?> (ANULADO) <?php endif; ?>
                        <br><b>Detalle:</b><br><?php echo nl2br(htmlspecialchars($res["detalledocumento"], ENT_QUOTES, 'UTF-8')); ?>
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
    } else {
        ?>
        <tr>
            <td colspan="2">Tipo de documento desconocido.</td>
        </tr>
        <?php
    }
    ?>
</table>

<?php include "plus/bottom.lm"; ?>
</body>
</html>
