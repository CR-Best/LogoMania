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

// Insertar nueva actividad si se recibe un formulario válido
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["agenda"])) {
    $dia = filter_input(INPUT_POST, "dia", FILTER_VALIDATE_INT);
    $mes = filter_input(INPUT_POST, "mes", FILTER_VALIDATE_INT);
    $year = filter_input(INPUT_POST, "year", FILTER_VALIDATE_INT);
    $horas = filter_input(INPUT_POST, "horas", FILTER_VALIDATE_INT);
    $minutos = filter_input(INPUT_POST, "minutos", FILTER_VALIDATE_INT);
    $ampm = filter_input(INPUT_POST, "24h", FILTER_VALIDATE_INT);
    $actividad = filter_input(INPUT_POST, "actividad", FILTER_SANITIZE_STRING);

    if ($dia && $mes && $year && $horas !== false && $minutos !== false) {
        $tiempo = sprintf("%04d-%02d-%02d", $year, $mes, $dia);

        // Convertir a formato de 24 horas
        if ($ampm == 12 && $horas != 12) {
            $horas += 12;
        } elseif ($ampm == 0 && $horas == 12) {
            $horas = 0;
        }
        $hora = sprintf("%02d:%02d:00", $horas, $minutos);

        $stmt = $conn->prepare("INSERT INTO actividades (idusuario, tiempo, hora, actividad) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $_SESSION["user"], $tiempo, $hora, $actividad);
        $stmt->execute();
        $stmt->close();
    }
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
    $result = $conn->query("SELECT idusuario, nombre FROM users");
    if ($result) {
        $usuarios = $result->fetch_all(MYSQLI_ASSOC);
    }
}

$conn->close();
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
