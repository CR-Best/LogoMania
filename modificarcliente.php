<?php
session_start();
require_once "db.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: sistema.php");
    exit();
}

$idcliente = intval($_GET["id"]);

// Obtener datos del cliente
$stmt = $conn->prepare("SELECT * FROM clientes WHERE idcliente = ?");
$stmt->bind_param("i", $idcliente);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: sistema.php");
    exit();
}

$datos = $result->fetch_assoc();
$stmt->close();

// Determinar la tabla de documentos
$tabla = ($datos["tipodocumento"] == 1) ? "ccf" : "cf";

$stmt = $conn->prepare("SELECT registrocliente, girocliente FROM documentos_{$tabla} WHERE idcliente = ?");
$stmt->bind_param("i", $idcliente);
$stmt->execute();
$documento = $stmt->get_result()->fetch_assoc() ?? ["registrocliente" => "", "girocliente" => ""];
$stmt->close();

include "plus/header.lm";
?>

<script>
function ini() {
    document.getElementById("nombrecliente").focus();
}

function cfiscal() {
    document.getElementById("registrocliente").value = "";
    document.getElementById("girocliente").value = "";
    document.getElementById("doc1").value = "Registro:";
    document.getElementById("doc2").value = "Giro:";
}

function cfinal() {
    document.getElementById("registrocliente").value = "";
    document.getElementById("girocliente").value = "";
    document.getElementById("doc1").value = "DUI:";
    document.getElementById("doc2").value = "NIT:";
}
</script>

<body onload="ini();">
<?php include "plus/top.lm"; ?>

<form action="modificarcliente1.php" method="post">
    <table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
        <tr>
            <td colspan="2" class="titulo">
                <img src="img/modificarcliente.jpg" width="300" height="75" />
            </td>
        </tr>
        <tr>
            <td width="24%"><b>Nombre del Cliente:</b></td>
            <td width="76%">
                <input name="nombrecliente" type="text" id="nombrecliente" size="50" value="<?php echo htmlspecialchars($datos["nombrecliente"], ENT_QUOTES, 'UTF-8'); ?>" />
                <input name="idcliente" type="hidden" value="<?php echo $idcliente; ?>" />
            </td>
        </tr>
        <tr>
            <td><b>Dirección:</b></td>
            <td><textarea name="dircliente" cols="50"><?php echo htmlspecialchars($datos["dircliente"], ENT_QUOTES, 'UTF-8'); ?></textarea></td>
        </tr>
        <tr>
            <td><b>Teléfono:</b></td>
            <td><input name="telcliente" type="text" size="15" value="<?php echo htmlspecialchars($datos["telcliente"], ENT_QUOTES, 'UTF-8'); ?>"></td>
        </tr>
        <tr>
            <td><b>Celular:</b></td>
            <td><input name="cellcliente" type="text" size="15" value="<?php echo htmlspecialchars($datos["cellcliente"], ENT_QUOTES, 'UTF-8'); ?>"></td>
        </tr>
        <tr>
            <td><b>FAX:</b></td>
            <td><input name="faxcliente" type="text" size="15" value="<?php echo htmlspecialchars($datos["faxcliente"], ENT_QUOTES, 'UTF-8'); ?>"></td>
        </tr>
        <tr>
            <td><b>Correo Electrónico:</b></td>
            <td><input name="correo" type="email" size="25" value="<?php echo htmlspecialchars($datos["emailcliente"], ENT_QUOTES, 'UTF-8'); ?>"></td>
        </tr>
        <tr>
            <td><b>Clase:</b></td>
            <td>
                <select name="clasecliente">
                    <option value="A" <?php echo ($datos["clasecliente"] == "A") ? "selected" : ""; ?>>A</option>
                    <option value="B" <?php echo ($datos["clasecliente"] == "B") ? "selected" : ""; ?>>B</option>
                    <option value="C" <?php echo ($datos["clasecliente"] == "C") ? "selected" : ""; ?>>C</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><b>Tipo de Consumidor:</b></td>
            <td>
                <input name="tipodocumento" type="radio" onclick="cfiscal()" value="1" <?php echo ($datos["tipodocumento"] == 1) ? "checked" : ""; ?>> <strong>C.C.F</strong>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input name="tipodocumento" type="radio" onclick="cfinal()" value="2" <?php echo ($datos["tipodocumento"] == 2) ? "checked" : ""; ?>> <strong>C.F.</strong>
            </td>
        </tr>
        <tr>
            <td><b>
                <input name="doc1" type="text" id="doc1" class="te" readonly value="<?php echo ($datos["tipodocumento"] == 1) ? "Registro:" : "DUI:"; ?>">
            </b></td>
            <td><input name="registrocliente" type="text" size="25" value="<?php echo htmlspecialchars($documento["registrocliente"], ENT_QUOTES, 'UTF-8'); ?>"></td>
        </tr>
        <tr>
            <td><b>
                <input name="doc2" type="text" id="doc2" class="te" readonly value="<?php echo ($datos["tipodocumento"] == 1) ? "Giro:" : "NIT:"; ?>">
            </b></td>
            <td><input name="girocliente" type="text" size="25" value="<?php echo htmlspecialchars($documento["girocliente"], ENT_QUOTES, 'UTF-8'); ?>"></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input name="enviar" type="submit" value="Actualizar">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="button" value="Cancelar" onclick="window.location.replace('sistema.php');">
            </td>
        </tr>
    </table>
</form>

<?php include "plus/bottom.lm"; ?>
