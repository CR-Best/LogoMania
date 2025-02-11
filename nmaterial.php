<?php
session_start();
require_once "db.php";

// Verificar sesión
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

$fmod = false;
$doc = $_GET["id"] ?? '';

// Eliminar material
if (isset($_GET["accion"]) && $_GET["accion"] == "eli" && isset($_GET["idmaterial"])) {
    $idmaterial = intval($_GET["idmaterial"]);

    // Verificar si el material está en inventario antes de eliminarlo
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM inventario WHERE idmaterial = ?");
    $stmt->bind_param("i", $idmaterial);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($row["total"] > 0) {
        header("Location: nmaterial.php?id=$doc&msg=material_en_uso");
        exit();
    }

    // Eliminar material
    $stmt = $conn->prepare("DELETE FROM material WHERE idmaterial = ?");
    $stmt->bind_param("i", $idmaterial);
    $stmt->execute();
    $stmt->close();

    header("Location: nmaterial.php?id=$doc&msg=material_eliminado");
    exit();
}

// Modificar material
if (isset($_GET["accion"]) && $_GET["accion"] == "mod" && isset($_GET["idmaterial"])) {
    $idmaterial = intval($_GET["idmaterial"]);
    $stmt = $conn->prepare("SELECT nombrematerial, medidamaterial FROM material WHERE idmaterial = ?");
    $stmt->bind_param("i", $idmaterial);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        header("Location: nmaterial.php?idmaterial=$idmaterial");
        exit();
    }

    $material = $result->fetch_assoc();
    $nombrematerial = $material["nombrematerial"];
    $medidamaterial = $material["medidamaterial"];
    $fmod = true;
}

// Agregar material
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["agregar"])) {
    $nombrematerial = trim($_POST["nombrematerial"]);
    $medidamaterial = trim($_POST["medidamaterial"]);

    if (empty($nombrematerial) || empty($medidamaterial)) {
        header("Location: nmaterial.php?id=$doc&msg=datos_invalidos");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO material (nombrematerial, medidamaterial) VALUES (?, ?)");
    $stmt->bind_param("ss", $nombrematerial, $medidamaterial);
    $stmt->execute();
    $idproducto = $stmt->insert_id;
    $stmt->close();

    header("Location: inventrada.php?doc=$doc&idmaterial=$idproducto");
    exit();
}

// Actualizar material
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["modificar"])) {
    $idmaterial = intval($_POST["idmaterial"]);
    $nombrematerial = trim($_POST["nombrematerial"]);
    $medidamaterial = trim($_POST["medidamaterial"]);

    if (empty($nombrematerial) || empty($medidamaterial)) {
        header("Location: nmaterial.php?id=$doc&msg=datos_invalidos");
        exit();
    }

    $stmt = $conn->prepare("UPDATE material SET nombrematerial = ?, medidamaterial = ? WHERE idmaterial = ?");
    $stmt->bind_param("ssi", $nombrematerial, $medidamaterial, $idmaterial);
    $stmt->execute();
    $stmt->close();

    header("Location: nmaterial.php?id=$doc&msg=material_actualizado");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Materiales</title>
    <link href="plus/estilo.css" rel="stylesheet" type="text/css">
</head>
<body>
<form action="nmaterial.php" method="post">
    <table width="100%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
        <tr>
            <td colspan="2" class="titulo">SISTEMA DE MATERIALES</td>
        </tr>
        <tr>
            <td>Nombre del Material:</td>
            <td>
                <input name="nombrematerial" type="text" size="40" value="<?= $fmod ? htmlspecialchars($nombrematerial) : ''; ?>" required>
                <input name="idcliente" type="hidden" value="<?= htmlspecialchars($doc); ?>">
            </td>
        </tr>
        <tr>
            <td>Código:</td>
            <td>
                <input name="idmaterial" type="text" size="6" maxlength="2" value="<?= $fmod ? htmlspecialchars($_GET["idmaterial"]) : ''; ?>" readonly>
            </td>
        </tr>
        <tr>
            <td>Medida de Material:</td>
            <td>
                <input name="medidamaterial" type="text" value="<?= $fmod ? htmlspecialchars($medidamaterial) : ''; ?>" required>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" name="<?= $fmod ? 'modificar' : 'agregar'; ?>" value="<?= $fmod ? 'Modificar' : 'Agregar'; ?>">
                <input type="button" value="Cerrar" onclick="window.close();">
            </td>
        </tr>
    </table>
</form>

<table width="95%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td class="titulo">Material</td>
        <td class="titulo">Existencia</td>
        <td class="titulo">Costo Promedio</td>
        <td class="titulo">Modificar</td>
        <td class="titulo">Eliminar</td>
    </tr>
    <?php
    $stmt = $conn->query("SELECT m.idmaterial, m.nombrematerial, m.medidamaterial, 
                                IFNULL(i.cantidad_material, 0) AS existencias, 
                                IFNULL(i.costomaterial, 0) AS costo 
                           FROM material m 
                           LEFT JOIN inventario i ON m.idmaterial = i.idmaterial 
                           ORDER BY m.nombrematerial ASC");

    while ($res = $stmt->fetch_assoc()):
    ?>
        <tr>
            <td><?= htmlspecialchars($res["nombrematerial"]); ?></td>
            <td align="center"><?= $res["existencias"] . " " . htmlspecialchars($res["medidamaterial"]); ?></td>
            <td align="center">$<?= number_format($res["costo"], 2, '.', ''); ?></td>
            <td align="center"><a href="nmaterial.php?idmaterial=<?= $res['idmaterial']; ?>&accion=mod&id=<?= $doc; ?>"><img src="img/modificar.png" border="0"></a></td>
            <td align="center"><a href="nmaterial.php?idmaterial=<?= $res['idmaterial']; ?>&accion=eli&id=<?= $doc; ?>"><img src="img/eliminar.png" border="0"></a></td>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
