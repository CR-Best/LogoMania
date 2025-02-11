<?php 
include("plus/conexion.lm");
include("plus/header.lm");
?>

<script>
function nv(pagina) {
    var int_windowLeft = (screen.width - 600) / 2;
    var int_windowTop = (screen.height - 400) / 2;
    window.open(pagina, 'usuarios', 'left=' + int_windowLeft + ',top=' + int_windowTop + ', width=600, height=400,toolbar=0,resizable=0, scrollbars=1');
}
</script>

<body>
<?php include("plus/top.lm"); ?>

<table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
    <tr>
        <td colspan="2" class="titulo"><strong>Actividades de Hoy (<?php echo date("d/m/Y"); ?>)</strong></td>
    </tr>
    <tr>
        <td width="50%"><strong>Pedidos Registrados:</strong></td>
        <td>
            <?php
            $stmt = $conectar->prepare("SELECT COUNT(*) AS total FROM pedidos WHERE DATE(fechapedido) = DATE(NOW())");
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            echo "<b>" . $result["total"] . "</b> pedidos registrados hoy.";
            ?>
            <a href="vpedido.php"><button>Ver Detalles</button></a>
        </td>
    </tr>
    <tr>
        <td width="50%"><strong>Documentos Emitidos:</strong></td>
        <td>
            <?php
            $stmt = $conectar->prepare("SELECT COUNT(*) AS total FROM ccf WHERE DATE(fechadocumento) = DATE(NOW())");
            $stmt->execute();
            $ccf = $stmt->get_result()->fetch_assoc()["total"];
            $stmt->close();

            $stmt = $conectar->prepare("SELECT COUNT(*) AS total FROM cf WHERE DATE(fechadocumento) = DATE(NOW())");
            $stmt->execute();
            $cf = $stmt->get_result()->fetch_assoc()["total"];
            $stmt->close();

            $total_docs = $ccf + $cf;
            echo "<b>$total_docs</b> documentos emitidos hoy (CCF: $ccf, CF: $cf).";
            ?>
            <a href="documentos.php"><button>Ver Detalles</button></a>
        </td>
    </tr>
</table>

<?php include("plus/bottom.lm"); ?>
</body>
</html>
