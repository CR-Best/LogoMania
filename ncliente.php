<?php
session_start();
require_once "db.php";
include "plus/header.lm";
?>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("nombrecliente").focus();
});

function validarFormulario() {
    let nombre = document.getElementById("nombrecliente").value.trim();
    let correo = document.getElementById("correo").value.trim();
    let telefono = document.getElementById("telcliente").value.trim();
    let tipoDoc = document.querySelector('input[name="tipodocumento"]:checked').value;
    let registro = document.getElementById("registrocliente").value.trim();
    let giro = document.getElementById("girocliente").value.trim();

    if (nombre === "") {
        alert("Ingrese el nombre del cliente.");
        return false;
    }

    if (correo !== "" && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo)) {
        alert("Ingrese un correo electrónico válido.");
        return false;
    }

    if (telefono !== "" && !/^\d{8,15}$/.test(telefono)) {
        alert("Ingrese un número de teléfono válido.");
        return false;
    }

    if (tipoDoc === "1" && (registro === "" || giro === "")) {
        alert("El registro y giro son obligatorios para C.C.F.");
        return false;
    }

    return true;
}
</script>

<body>

<?php include "plus/top.lm"; ?>

<form action="ncliente1.php" method="post" name="ncliente" onsubmit="return validarFormulario();">
    <table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
        <tr>
            <td colspan="2" class="titulo"><img src="img/acliente.jpg" width="300" height="75"></td>
        </tr>
        <tr>
            <td width="24%"><b>Nombre del Cliente:</b></td>
            <td width="76%"><input name="nombrecliente" type="text" id="nombrecliente" size="50" required></td>
        </tr>
        <tr>
            <td><b>Dirección:</b></td>
            <td><textarea name="dircliente" cols="50" id="dircliente"></textarea></td>
        </tr>
        <tr>
            <td><b>Teléfono:</b></td>
            <td><input name="telcliente" type="text" id="telcliente" size="15"></td>
        </tr>
        <tr>
            <td><b>Celular:</b></td>
            <td><input name="cellcliente" type="text" id="cellcliente" size="15"></td>
        </tr>
        <tr>
            <td><b>FAX:</b></td>
            <td><input name="faxcliente" type="text" id="faxcliente" size="15"></td>
        </tr>
        <tr>
            <td><b>Correo Electrónico:</b></td>
            <td><input name="correo" type="email" id="correo" size="25"></td>
        </tr>
        <tr>
            <td><b>Clase:</b></td>
            <td>
                <select name="clasecliente" id="clasecliente">
                    <option>A</option>
                    <option>B</option>
                    <option>C</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><b>Tipo de consumidor:</b></td>
            <td>
                <input name="tipodocumento" type="radio" value="1" onclick="document.getElementById('doc1').value='Registro:'; document.getElementById('doc2').value='Giro:';"> <b>C.C.F</b>
                <input name="tipodocumento" type="radio" value="2" checked onclick="document.getElementById('doc1').value='DUI:'; document.getElementById('doc2').value='NIT:';"> <b>C.F.</b>
            </td>
        </tr>
        <tr>
            <td><b><input name="doc1" type="text" id="doc1" value="DUI:" class="te" readonly></b></td>
            <td><input name="registrocliente" type="text" id="registrocliente" size="25"></td>
        </tr>
        <tr>
            <td><b><input name="doc2" type="text" id="doc2" value="NIT:" class="te" readonly></b></td>
            <td><input name="girocliente" type="text" id="girocliente" size="25"></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input name="enviar" type="submit" id="enviar" value="Procesar">
                <input type="reset" value="Restablecer">
                <input type="button" value="Cancelar" onclick="window.location='sistema.php';">
            </td>
        </tr>
    </table>
</form>

<?php include "plus/bottom.lm"; ?>

</body>
</html>
