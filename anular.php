<?php
session_start();
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["flag"])) {
    $id_documento = intval($_POST["iddocumento"]);
    $tipo_documento = intval($_POST["tipodocumento"]);
    $motivo = htmlspecialchars($_POST["motivo"]);
    $fecha = $_POST["apedido"] . "-" . $_POST["mpedido"] . "-" . $_POST["dpedido"];

    // Insertar documento anulado
    $stmt = $conn->prepare("INSERT INTO documentos_anulados (iddocumento, tipodocumento, fechaanulada, motivo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $id_documento, $tipo_documento, $fecha, $motivo);
    $stmt->execute();
    $stmt->close();

    header("Location: anular.php");
    exit();
}

// Si se envió un número de documento para anular
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["numdoc"])) {
    $numdoc = intval($_POST["numdoc"]);
    $tipo_documento = intval($_POST["tipodocumento"]);
    
    $tabla = ($tipo_documento == 1) ? "ccf" : "cf";
    $titulo_documento = ($tipo_documento == 1) ? "Comprobante de Crédito Fiscal #" : "Consumidor Final #";

    // Obtener datos del documento
    $stmt = $conn->prepare("SELECT * FROM $tabla WHERE iddocumento = ?");
    $stmt->bind_param("i", $numdoc);
    $stmt->execute();
    $datos_factura = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$datos_factura) {
        header("Location: anular.php");
        exit();
    }

    $fecha_emision = new DateTime($datos_factura["fechadocumento"]);
}

include "plus/header.lm";
?>

<script>
function nv(pagina) {
    var int_windowLeft = (screen.width - 600) / 2;
    var int_windowTop = (screen.height - 400) / 2;
    window.open(pagina, 'usuarios', 'left=' + int_windowLeft + ',top=' + int_windowTop + ', width=600, height=400,toolbar=0,resizable=0, scrollbars=1');
}
</script>

<body>
<?php include "plus/top.lm"; ?>

<?php if (!isset($_POST["numdoc"])): ?>
    <form action="anular.php" method="post">
        <table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
            <tr>
                <td colspan="2" class="titulo"><strong>Anular documento</strong></td>
            </tr>
            <tr>
                <td width="50%">Ingrese el número de documento:</td>
                <td><input name="numdoc" type="text"></td>        
            </tr>
            <tr>
                <td>Seleccione el tipo de documento:</td>
                <td>
                    <input name="tipodocumento" type="radio" value="1" checked> CCF 
                    <input name="tipodocumento" type="radio" value="2"> CF
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center"><input type="submit" name="Submit" value="Enviar"></td>
            </tr>
        </table>
    </form>
<?php else: ?>
    <form action="anular.php" method="post">
        <table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
            <tr>
                <td colspan="2" class="titulo"><strong>Anular documento</strong></td>
            </tr>
            <tr>
                <td><?php echo $titulo_documento; ?>:</td>
                <td>
                    <input type="text" name="iddocumento" value="<?php echo $numdoc; ?>" readonly>
                    <input type="hidden" name="tipodocumento" value="<?php echo $tipo_documento; ?>">
                </td>
            </tr>
            <tr>
                <td>Emitido a nombre de:</td>
                <td>
                    <?php
                    $stmt = $conn->prepare("SELECT nombrecliente FROM clientes WHERE idcliente = ?");
                    $stmt->bind_param("i", $datos_factura["idcliente"]);
                    $stmt->execute();
                    $cliente = $stmt->get_result()->fetch_assoc();
                    $stmt->close();

                    echo htmlspecialchars($cliente["nombrecliente"]);
                    ?>
                </td>
            </tr>
            <tr>
                <td>Fecha de anulación:</td>
                <td>
                    <input type="date" name="fecha_anulacion" value="<?php echo date('Y-m-d'); ?>" required>
                </td>
            </tr>
            <tr>
                <td>Motivo:</td>
                <td><textarea name="motivo" cols="50" rows="5"></textarea></td>
            </tr>
            <tr>
                <td colspan="2">Detalles:<br><?php echo htmlspecialchars($datos_factura["detalledocumento"]); ?></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="hidden" name="flag">
                    <input type="submit" name="Submit" value="Anular">
                    <input type="button" onclick="window.location.href='sistema.php';" value="Cancelar">
                </td>
            </tr>
        </table>
    </form>
<?php endif; ?>

<?php include "plus/bottom.lm"; ?>
</body>
</html>
