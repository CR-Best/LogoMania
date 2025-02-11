<?php
session_start();
require_once "db.php"; // Conexión segura con MySQLi

// Verificar sesión activa
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

// Manejo de acciones (Agregar, Modificar, Eliminar)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ac"])) {
    $idusuario = htmlspecialchars($_POST["idusuario"]);
    $nombre = htmlspecialchars($_POST["nombre"]);
    $nivel = intval($_POST["nivel"]);
    $accion = $_POST["ac"];

    if ($accion == "nu") { // Agregar usuario
        $password = password_hash($_POST["pass1"], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (idusuario, nombre, password, nivel) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $idusuario, $nombre, $password, $nivel);
        $stmt->execute();
        header("Location: users.php?comp=1");
        exit();
    }

    if ($accion == "mod") { // Modificar usuario
        if (!empty($_POST["pass1"])) {
            $password = password_hash($_POST["pass1"], PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET nombre=?, password=?, nivel=? WHERE idusuario=?");
            $stmt->bind_param("ssis", $nombre, $password, $nivel, $idusuario);
        } else {
            $stmt = $conn->prepare("UPDATE users SET nombre=?, nivel=? WHERE idusuario=?");
            $stmt->bind_param("sis", $nombre, $nivel, $idusuario);
        }
        $stmt->execute();
        header("Location: users.php?comp=2");
        exit();
    }

    if ($accion == "eli") { // Eliminar usuario
        $stmt = $conn->prepare("DELETE FROM users WHERE idusuario=?");
        $stmt->bind_param("s", $idusuario);
        $stmt->execute();
        header("Location: users.php?comp=3");
        exit();
    }
}

// Validación de acceso
$accion = isset($_GET["comp"]) ? 4 : 0;
if (isset($_POST["password"]) && password_verify($_POST["password"], $_SESSION["pass"])) {
    $accion = $_POST["accion"] == "nu" ? 1 : ($_POST["accion"] == "mod" ? 2 : 3);
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Usuarios</title>
    <link rel="stylesheet" href="plus/estilo.css">
    <script>
        function confirmarEliminacion(url) {
            if (confirm("¿Está seguro de que desea eliminar este usuario?")) {
                window.location.href = url;
            }
        }
    </script>
</head>
<body>
<?php if ($accion == 1): ?>
    <form action="users.php" method="post">
        <table border="3" align="center">
            <tr><td colspan="2" class="titulo">Agregar Usuario</td></tr>
            <tr><td>Nombre:</td><td><input type="text" name="nombre" required></td></tr>
            <tr><td>ID Usuario:</td><td><input type="text" name="idusuario" required></td></tr>
            <tr><td>Password:</td><td><input type="password" name="pass1" required></td></tr>
            <tr><td>Confirmar Password:</td><td><input type="password" name="pass2" required></td></tr>
            <tr><td>Nivel:</td><td>
                <select name="nivel">
                    <option value="1">Administrador</option>
                    <option value="2" selected>Usuario</option>
                </select>
            </td></tr>
            <tr><td colspan="2" align="center">
                <input type="hidden" name="ac" value="nu">
                <input type="submit" value="Agregar">
                <input type="button" value="Cerrar" onclick="window.close();">
            </td></tr>
        </table>
    </form>
<?php endif; ?>

<?php if ($accion == 2): ?>
    <?php
    $stmt = $conn->prepare("SELECT nombre, nivel FROM users WHERE idusuario=?");
    $stmt->bind_param("s", $_GET["id"]);
    $stmt->execute();
    $usuario = $stmt->get_result()->fetch_assoc();
    ?>
    <form action="users.php" method="post">
        <table border="3" align="center">
            <tr><td colspan="2" class="titulo">Modificar Usuario</td></tr>
            <tr><td>Nombre:</td><td><input type="text" name="nombre" value="<?= htmlspecialchars($usuario["nombre"]); ?>" required></td></tr>
            <tr><td>ID Usuario:</td><td><input type="text" name="idusuario" value="<?= htmlspecialchars($_GET["id"]); ?>" readonly></td></tr>
            <tr><td>Password (dejar vacío para no cambiar):</td><td><input type="password" name="pass1"></td></tr>
            <tr><td>Confirmar Password:</td><td><input type="password" name="pass2"></td></tr>
            <tr><td>Nivel:</td><td>
                <select name="nivel">
                    <option value="1" <?= $usuario["nivel"] == 1 ? "selected" : ""; ?>>Administrador</option>
                    <option value="2" <?= $usuario["nivel"] == 2 ? "selected" : ""; ?>>Usuario</option>
                </select>
            </td></tr>
            <tr><td colspan="2" align="center">
                <input type="hidden" name="ac" value="mod">
                <input type="submit" value="Modificar">
                <input type="button" value="Cerrar" onclick="window.close();">
            </td></tr>
        </table>
    </form>
<?php endif; ?>

<?php if ($accion == 3): ?>
    <form action="users.php" method="post">
        <table border="3" align="center">
            <tr><td colspan="2" class="titulo">Eliminar Usuario</td></tr>
            <tr><td>Nombre:</td><td><?= htmlspecialchars($_GET["id"]); ?></td></tr>
            <tr><td colspan="2" align="center">
                <input type="hidden" name="ac" value="eli">
                <input type="hidden" name="idusuario" value="<?= htmlspecialchars($_GET["id"]); ?>">
                <input type="submit" value="Eliminar">
                <input type="button" value="Cerrar" onclick="window.close();">
            </td></tr>
        </table>
    </form>
<?php endif; ?>

<?php if ($accion == 4): ?>
    <table border="3" align="center">
        <tr><td class="titulo">Sistema de Usuarios</td></tr>
        <tr><td>
            <?php
            echo $_GET["comp"] == 1 ? "Usuario agregado correctamente." :
                ($_GET["comp"] == 2 ? "Usuario modificado correctamente." :
                    ($_GET["comp"] == 3 ? "Usuario eliminado correctamente." : ""));
            echo "<br>Esta ventana se cerrará en 3 segundos.";
            ?>
        </td></tr>
    </table>
<?php endif; ?>
</body>
</html>
