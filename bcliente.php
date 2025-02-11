<?php
session_start();
require_once "db.php";
include "plus/header.lm";
?>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("busqueda").focus();
});

function cancelarBusqueda() {
    window.location.href = "sistema.php";
}
</script>

</head>
<body>

<?php include "plus/top.lm"; ?>

<form action="bcliente1.php" method="post">
    <table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
        <tr>
            <td colspan="2" class="titulo">
                <img src="img/bcliente.jpg" width="300" height="75" />
            </td>
        </tr>
        <tr>
            <td width="24%"><label for="busqueda"><b>Nombre del Cliente:</b></label></td>
            <td width="76%">
                <input name="busqueda" id="busqueda" type="text" size="50" required />
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" name="Submit" value="Buscar" />
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="button" name="Submit2" value="Cancelar" onclick="cancelarBusqueda();" />
            </td>
        </tr>
    </table>
</form>

<?php include "plus/bottom.lm"; ?>
</body>
</html>
