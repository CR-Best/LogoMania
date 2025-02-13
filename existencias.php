<?php
session_start();
require_once "db.php";
include "plus/header.lm";
?>

<body>
<?php include "plus/top.lm"; ?>

<table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
    <?php
    $sql = "
        SELECT m.idmaterial, m.nombrematerial, m.medidamaterial, 
               COALESCE(i.cantidad_material, 0) AS existencias, 
               COALESCE(i.costomaterial, 0) AS costo
        FROM material m
        LEFT JOIN inventario i ON m.idmaterial = i.idmaterial
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $total = $result->num_rows;
    $stmt->close();

    echo "<tr><td colspan=3 class='titulo'>Registros encontrados: <b>$total</b></td></tr>";
    ?>
    <tr>
        <td class="titulo"><b>Nombre del Material</b></td>
        <td align="center" class="titulo">Costo Promedio</td>
        <td align="center" class="titulo">Existencias</td>
    </tr>
    <?php while ($res = $result->fetch_assoc()): ?>
        <tr>
            <td>
                <?php if ($res["existencias"] > 0): ?>
                    <a href="invsalida.php?idmaterial=<?php echo intval($res['idmaterial']); ?>">
                        <?php echo htmlspecialchars($res["nombrematerial"], ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                <?php else: ?>
                    <?php echo htmlspecialchars($res["nombrematerial"], ENT_QUOTES, 'UTF-8'); ?>
                <?php endif; ?>
            </td>
            <td align="center">$ <?php echo number_format(floatval($res["costo"]), 2, '.', ''); ?></td>
            <td align="center"><?php echo intval($res["existencias"]) . " " . htmlspecialchars($res["medidamaterial"], ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
    <?php endwhile; ?>
</table>

<?php include "plus/bottom.lm"; ?>
</body>
</html>
