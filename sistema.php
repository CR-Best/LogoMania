<?php
session_start();
require_once "db.php";

if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

$mensaje = "";
if (isset($_GET["msg"])) {
    $mensajes = [
        "ca" => "Cliente Agregado Satisfactoriamente.",
        "cac" => "Cliente Actualizado Satisfactoriamente.",
        "cb" => "Cliente Eliminado Satisfactoriamente."
    ];
    if (array_key_exists($_GET["msg"], $mensajes)) {
        $mensaje = "<marquee behavior='alternate' scrollamount='15'><span class='mensajes' align='center'>{$mensajes[$_GET["msg"]]}</span></marquee><br><br>";
    }
}

// Limpiar actividades antiguas
$fecha_limite = date("Y-m-d", strtotime("-5 days"));
$conn->query("DELETE FROM actividades WHERE tiempo <= '$fecha_limite'");

// Insertar nueva actividad
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["agenda"])) {
    $tiempo = $_POST["year"] . "-" . $_POST["mes"] . "-" . $_POST["dia"];
    $hora = $_POST["horas"] . ":" . $_POST["minutos"] . ":00";
    if ($_POST["24h"] == 12 && $_POST["horas"] != 12) {
        $hora = ($_POST["horas"] + 12) . ":" . $_POST["minutos"] . ":00";
    }
    
    $stmt = $conn->prepare("INSERT INTO actividades (idusuario, tiempo, hora, actividad) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $_SESSION["user"], $tiempo, $hora, $_POST["actividad"]);
    $stmt->execute();
    $stmt->close();
}

// Obtener actividades del día
$fechahoy = date("Y-m-d");
$stmt = $conn->prepare("SELECT hora, actividad FROM actividades WHERE idusuario = ? AND tiempo = ? ORDER BY hora ASC");
$stmt->bind_param("ss", $_SESSION["user"], $fechahoy);
$stmt->execute();
$result = $stmt->get_result();
$actividades = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Consultar pedidos en proceso y pendientes
$pedidos_proceso = $conn->query("SELECT COUNT(*) as total FROM pedidos WHERE estadopedido = 2")->fetch_assoc()["total"];
$pedidos_pendientes = $conn->query("SELECT COUNT(*) as total FROM pedidos WHERE estadopedido = 1")->fetch_assoc()["total"];

// Consultar próxima fecha de entrega
$proxima_fecha = "No disponible";
$result = $conn->query("SELECT fechaentrega FROM pedidos ORDER BY fechaentrega ASC LIMIT 1");
if ($row = $result->fetch_assoc()) {
    $fecha_parts = explode("-", $row["fechaentrega"]);
    $proxima_fecha = "{$fecha_parts[2]}/{$fecha_parts[1]}/{$fecha_parts[0]}";
}

// Obtener lista de usuarios si el usuario tiene nivel 1
$usuarios = [];
if ($_SESSION["nivel"] == 1) {
    $usuarios = $conn->query("SELECT idusuario, nombre FROM users")->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Control</title>
    <link href="plus/estilo.css" rel="stylesheet" type="text/css">
</head>
<body>
    <table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
        <tr>
            <td width="50%" class="titulo">Bienvenido <i><?php echo htmlspecialchars($_SESSION["nombre"]); ?></i></td>
            <td class="titulo">REGISTRO DE PEDIDOS</td>
        </tr>
        <tr>
            <td>
                <strong>Actividades de Hoy:</strong><br><br>
                <?php if (empty($actividades)): ?>
                    No hay actividades registradas para este día.
                <?php else: ?>
                    <?php foreach ($actividades as $act): ?>
                        <strong><?php echo date("h:i A", strtotime($act["hora"])); ?>:</strong> <?php echo htmlspecialchars($act["actividad"]); ?><br><hr>
                    <?php endforeach; ?>
                <?php endif; ?>

                <form action="sistema.php" method="post">
                    <table width="95%" border="1" cellspacing="0" cellpadding="0">
                        <tr>
                            <td colspan="2" class="titulo">Agregar actividad</td>
                        </tr>
                        <tr>
                            <td><strong>Día</strong></td>
                            <td>
                                <input name="dia" type="number" value="<?php echo date("d"); ?>" min="1" max="31" required>
                                -
                                <select name="mes">
                                    <?php
                                    $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
                                    foreach ($meses as $i => $mes) {
                                        $selected = ($i + 1 == date("m")) ? "selected" : "";
                                        echo "<option value='" . ($i + 1) . "' $selected>$mes</option>";
                                    }
                                    ?>
                                </select>
                                -
                                <input name="year" type="number" value="<?php echo date("Y"); ?>" min="<?php echo date("Y"); ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Hora</strong></td>
                            <td>
                                <input name="horas" type="number" min="1" max="12" value="<?php echo date("h"); ?>" required>
                                :
                                <input name="minutos" type="number" min="0" max="59" value="<?php echo date("i"); ?>" required>
                                <select name="24h">
                                    <option value="0">AM</option>
                                    <option value="12" <?php echo (date("A") == "PM") ? "selected" : ""; ?>>PM</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Descripción</strong></td>
                            <td><textarea name="actividad" cols="35" rows="4" required></textarea></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center"><input type="submit" name="agenda" value="Agregar!"></td>
                        </tr>
                    </table>
                </form>
            </td>
            <td>
                <strong>En proceso:</strong> <?php echo $pedidos_proceso; ?><br>
                <strong>Pendientes:</strong> <?php echo $pedidos_pendientes; ?><br>
                <strong>Próxima entrega:</strong> <?php echo $proxima_fecha; ?>
            </td>
        </tr>
    </table>
</body>
</html>
