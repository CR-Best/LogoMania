<?php
session_start();
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["finalizar"])) {
    $idmaterial = intval($_POST["idmaterial"]);
    $num_doc = htmlspecialchars($_POST["num"]);
    $cantidad_material = floatval($_POST["cantidad_material"]);
    $precio_unitario = floatval($_POST["preciounitario"]);
    $fechaentrada = "{$_POST["year"]}-{$_POST["mes"]}-{$_POST["dia"]}";

    if ($idmaterial <= 0 || $cantidad_material <= 0 || $precio_unitario <= 0) {
        header("Location: inventrada.php?msg=invalid_input");
        exit();
    }

    // Verificar si el material ya existe en inventario
    $stmt = $conn->prepare("SELECT cantidad_material, costomaterial FROM inventario WHERE idmaterial = ?");
    $stmt->bind_param("i", $idmaterial);
    $stmt->execute();
    $result = $stmt->get_result();
    $flage = ($result->num_rows > 0);

    if ($flage) {
        $row = $result->fetch_assoc();
        $new_cantidad = $row["cantidad_material"] + $cantidad_material;
        $new_costo = (($precio_unitario * $cantidad_material) + ($row["costomaterial"] * $row["cantidad_material"])) / $new_cantidad;
    } else {
        $new_cantidad = $cantidad_material;
        $new_costo = $precio_unitario;
    }
    $stmt->close();

    // Insertar la entrada en inventario
    $stmt = $conn->prepare("INSERT INTO entradas_inventario (fechaentrada, ndocumento, idmaterial, cantidad_material, costomaterial) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssidd", $fechaentrada, $num_doc, $idmaterial, $cantidad_material, $precio_unitario);
    $stmt->execute();
    $stmt->close();

    // Actualizar o insertar en inventario
    if ($flage) {
        $stmt = $conn->prepare("UPDATE inventario SET cantidad_material = ?, costomaterial = ? WHERE idmaterial = ?");
        $stmt->bind_param("ddi", $new_cantidad, $new_costo, $idmaterial);
    } else {
        $stmt = $conn->prepare("INSERT INTO inventario (idmaterial, cantidad_material, costomaterial) VALUES (?, ?, ?)");
        $stmt->bind_param("idd", $idmaterial, $new_cantidad, $new_costo);
    }
    $stmt->execute();
    $stmt->close();

    // Redirección tras éxito
    $redirect_url = ($_POST["ag"] == 2) ? "inventrada.php?idmaterial=$idmaterial&doc=$num_doc&msg=ok" : "existencias.php";
    header("Location: $redirect_url");
    exit();
}

include "plus/header.lm";
?>

<script>
function nv(pagina) {
    var int_windowLeft = (screen.width - 600) / 2;
    var int_windowTop = (screen.height - 400) / 2;
    if (document.inventrada.idmaterial.value == 1) {
        var conca = pagina + '?id=' + document.inventrada.num.value;
        window.open(conca, 'inventario', 'left=' + int_windowLeft + ',top=' + int_windowTop + ', width=600, height=400,toolbar=0,resizable=0, scrollbars=1');
    }
}
</script>

<?php include "plus/top.lm"; ?>

<form action="inventrada.php" method="post" name="inventrada">
    <table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
        <tr>
            <td colspan="2" class="titulo"><img src="img/inventrada.jpg" width="300" height="75" /></td>
        </tr>
        <tr>
            <td width="24%"><b>Número de Documento :</b></td>
            <td width="76%">
                <input name="num" type="text" id="num" size="10" value="<?php echo isset($_GET["doc"]) ? htmlspecialchars($_GET["doc"]) : ''; ?>" readonly>
            </td>
        </tr>
        <tr>
            <td><b>Material o Producto:</b></td>
            <td>
                <select name="idmaterial" id="idmaterial" onchange="nv('nmaterial.php')">
                    <?php
                    $idmaterial = isset($_GET["idmaterial"]) ? intval($_GET["idmaterial"]) : 0;
                    $stmt = $conn->prepare("SELECT idmaterial, nombrematerial FROM material ORDER BY nombrematerial ASC");
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()) {
                        echo "<option value=\"{$row["idmaterial"]}\" " . ($row["idmaterial"] == $idmaterial ? "selected" : "") . ">" . htmlspecialchars($row["nombrematerial"]) . "</option>";
                    }
                    ?>
                    <option value="0">Seleccione un material</option>
                    <option value="1">Agregar nuevo material</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><b>Precio Unitario :</b></td>
            <td>$ <input name="preciounitario" type="text" id="preciounitario" size="10"></td>
        </tr>
        <tr>
            <td><b>Fecha de Compra :</b></td>
            <td>
                <input type="date" name="fecha_compra" value="<?php echo date('Y-m-d'); ?>">
            </td>
        </tr>
        <tr>
            <td><b>Cantidad:</b></td>
            <td><input name="cantidad_material" type="text" id="cantidad_material" size="10"></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input name="ag" type="hidden" value="1">
                <input type="submit" name="finalizar" value="Finalizar">
                <input type="button" value="Cancelar" onclick="window.location.replace('existencias.php');">
            </td>
        </tr>
    </table>
</form>

<?php include "plus/bottom.lm"; ?>
