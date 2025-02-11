<?php 
session_start();
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["idmaterial"]) && $_POST["idmaterial"] !== "1" && !isset($_POST["ag"])) {
        header("Location: invsalida.php?idmaterial=" . intval($_POST["idmaterial"]));
        exit();
    }

    if (isset($_POST["ag"])) {
        $idmaterial = intval($_POST["idmaterial"]);
        $cantidad_actual = floatval($_POST["cantidadactual"]);
        $cantidad_salida = floatval($_POST["cantidad_material"]);

        if ($cantidad_salida <= 0 || $cantidad_salida > $cantidad_actual) {
            header("Location: invsalida.php?msg=invalid_quantity");
            exit();
        }

        $conn->begin_transaction();
        try {
            // Actualizar inventario
            $stmt = $conn->prepare("UPDATE inventario SET cantidad_material = cantidad_material - ? WHERE idmaterial = ?");
            $stmt->bind_param("di", $cantidad_salida, $idmaterial);
            $stmt->execute();
            $stmt->close();

            // Insertar salida de inventario
            $stmt = $conn->prepare("INSERT INTO salidas_inventario (fecha, idmaterial, cantidad, costo) VALUES (CURDATE(), ?, ?, (SELECT costomaterial FROM inventario WHERE idmaterial = ?))");
            $stmt->bind_param("idi", $idmaterial, $cantidad_salida, $idmaterial);
            $stmt->execute();
            $stmt->close();

            $conn->commit();

            header("Location: " . ($_POST["ag"] == 2 ? "invsalida.php" : "existencias.php"));
            exit();
        } catch (Exception $e) {
            $conn->rollback();
            header("Location: invsalida.php?msg=error");
            exit();
        }
    }
}

include "plus/header.lm";
?>

<script>
function validarSalida() {
    var cantidad = parseFloat(document.getElementById("cantidad_material").value);
    var cantidadDisponible = parseFloat(document.getElementById("cantidadactual").value);
    
    if (isNaN(cantidad) || cantidad <= 0) {
        alert("Ingrese una cantidad válida.");
        return false;
    }
    if (cantidad > cantidadDisponible) {
        alert("No hay suficiente material disponible. Disponible: " + cantidadDisponible);
        return false;
    }

    return confirm("¿Desea continuar con la salida de inventario?");
}
</script>

<?php include "plus/top.lm"; ?>

<?php if (!isset($_GET["idmaterial"])): ?>
    <form action="invsalida.php" method="post">
        <table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
            <tr>
                <td colspan="2" class="titulo">
                    <img src="img/invsalida.jpg" width="300" height="75" />
                </td>
            </tr>
            <tr>
                <td width="24%"><b>Nombre de Material:</b></td>
                <td width="76%">
                    <select name="idmaterial" id="idmaterial">
                        <option value="1">Seleccione un producto</option>
                        <?php
                        $stmt = $conn->prepare("SELECT idmaterial, nombrematerial FROM material ORDER BY nombrematerial ASC");
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . intval($row["idmaterial"]) . "'>" . htmlspecialchars($row["nombrematerial"]) . "</option>";
                        }
                        $stmt->close();
                        ?>
                    </select>    
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" name="buscar" value="Buscar">
                    <input type="button" value="Cancelar" onclick="window.location.replace('existencias.php');">
                </td>
            </tr>
        </table>
    </form>
<?php endif; ?>

<?php if (isset($_GET["idmaterial"])): ?>
    <?php
    $idmaterial = intval($_GET["idmaterial"]);
    $stmt = $conn->prepare("SELECT nombrematerial FROM material WHERE idmaterial = ?");
    $stmt->bind_param("i", $idmaterial);
    $stmt->execute();
    $material = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $stmt = $conn->prepare("SELECT cantidad_material, costomaterial FROM inventario WHERE idmaterial = ?");
    $stmt->bind_param("i", $idmaterial);
    $stmt->execute();
    $inventario = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $cantidadDisponible = $inventario["cantidad_material"] ?? 0;
    $costoMaterial = $inventario["costomaterial"] ?? 0;
    ?>

    <form action="invsalida.php" method="post" onsubmit="return validarSalida();">
        <table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
            <tr>
                <td colspan="2" class="titulo">
                    <img src="img/invsalida.jpg" width="300" height="75" />
                </td>
            </tr>
            <tr>
                <td width="24%"><b>Nombre del Material:</b></td>
                <td width="76%">
                    <input type="text" name="nombrematerial" value="<?php echo htmlspecialchars($material["nombrematerial"]); ?>" readonly>
                    <input type="hidden" name="idmaterial" value="<?php echo $idmaterial; ?>">
                </td>
            </tr>
            <tr>
                <td width="24%"><b>Cantidad a utilizar:</b></td>
                <td width="76%">
                    <input type="number" name="cantidad_material" id="cantidad_material" min="1" step="0.01">
                    <input type="hidden" name="cantidadactual" id="cantidadactual" value="<?php echo $cantidadDisponible; ?>">
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="hidden" name="ag" value="1">
                    <input type="submit" name="finalizar" value="Finalizar">
                    <input type="button" value="Cancelar" onclick="window.location.replace('existencias.php');">
                </td>
            </tr>
        </table>
    </form>
<?php endif; ?>

<?php include "plus/bottom.lm"; ?>
