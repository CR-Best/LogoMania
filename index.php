<?php
session_start();
require_once "db.php";

$error = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = filter_input(INPUT_POST, "user", FILTER_SANITIZE_STRING);
    $pass = filter_input(INPUT_POST, "pass", FILTER_SANITIZE_STRING);

    if ($user && $pass) {
        $stmt = $conn->prepare("SELECT nombre, password, nivel FROM users WHERE idusuario = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($nombre, $hashed_password, $nivel);
            $stmt->fetch();

            if (password_verify($pass, $hashed_password)) {
                $_SESSION["user"] = $user;
                $_SESSION["nombre"] = $nombre;
                $_SESSION["nivel"] = $nivel;
                
                $stmt->close();
                $conn->close();
                
                header("Location: sistema.php");
                exit();
            }
        }
        $stmt->close();
    }
    $error = true;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido a LOGOSYS</title>
    <link href="plus/estilo.css" rel="stylesheet" type="text/css">
    <script>
        function iniciar() {
            document.getElementById("user").focus();
        }
    </script>
</head>
<body onload="iniciar();">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td><img src="top.jpg" align="left" /></td>
        </tr>
        <tr>
            <td bgcolor="#FFFFFF">
                <form action="index.php" method="post">
                    <br><br>
                    <?php if ($error): ?>
                        <span class="mensajes">Usuario o contraseña incorrectos. Inténtelo de nuevo.</span>
                    <?php endif; ?>

                    <table width="50%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
                        <tr>
                            <td colspan="2" class="titulo">Ingreso al Sistema:</td>
                        </tr>
                        <tr>
                            <td><strong>Nombre de Usuario</strong></td>
                            <td><input name="user" type="text" id="user" size="25" required></td>
                        </tr>
                        <tr>
                            <td><strong>Contraseña</strong></td>
                            <td><input name="pass" type="password" id="pass" size="25" required></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center">
                                <input type="submit" name="Submit" value="Ingresar">
                            </td>
                        </tr>
                    </table>
                    <br><br>
                </form>
            </td>
        </tr>
        <tr>
            <td align="center" bgcolor="#FFFFFF">
                <img src="img/derechos.jpg" />
            </td>
        </tr>
    </table>
</body>
</html>
