<?php
session_start();
require_once "db.php"; // Asegúrate de que "db.php" usa mysqli con conexión segura

// Verificar sesión
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

// Mensaje de éxito
$mensaje = isset($_GET["msg"]) ? "<marquee><span class='mensajes' align='center'>Pedido Agregado Satisfactoriamente.</span></marquee><br><br>" : "";

// Obtener lista de clientes
$stmt = $conn->query("SELECT idcliente, nombrecliente FROM clientes ORDER BY nombrecliente ASC");
$clientes = $stmt->fetch_all(MYSQLI_ASSOC);

// Obtener lista de productos
$stmt = $conn->query("SELECT idproducto, nombreproducto, precioventaproducto FROM productos");
$productos = $stmt->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Pedido</title>
    <link rel="stylesheet" href="plus/estilo.css">
    <script>
        function calcularTotal() {
            let cantidad = parseFloat(document.pedidos.cantidadproducto.value) || 0;
            let precio = parseFloat(document.pedidos.precioventaproducto.value) || 0;
            document.pedidos.total.value = (cantidad * precio).toFixed(2);
            document.pedidos.anticipo.value = (cantidad * precio / 2).toFixed(2);
        }

        function confirmarPedido() {
            return confirm("¿Desea agregar este pedido?");
        }
    </script>
</head>
<body>
    <?php include("plus/top.lm"); ?>
    
    <?= $mensaje; ?>

    <form action="npedido1.php" method="post" name="pedidos" onsubmit="return confirmarPedido();">
        <table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
            <tr>
                <td colspan="2" class="titulo"><img src="img/npedido.jpg" width="300" height="75"></td>
            </tr>
            <tr>
                <td><b>Nombre del Cliente:</b></td>
                <td>
                    <select name="idclientes" id="idclientes" required>
                        <option value="">Seleccione un cliente</option>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?= $cliente['idcliente']; ?>"><?= htmlspecialchars($cliente['nombrecliente']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><b>Producto:</b></td>
                <td>
                    <select name="idproducto" id="idproducto" required onchange="calcularTotal();">
                        <option value="">Seleccione un producto</option>
                        <?php foreach ($productos as $producto): ?>
                            <option value="<?= $producto['idproducto']; ?>" data-precio="<?= $producto['precioventaproducto']; ?>">
                                <?= htmlspecialchars($producto['nombreproducto']); ?> - $<?= number_format($producto['precioventaproducto'], 2); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><b>Cantidad:</b></td>
                <td><input type="number" name="cantidadproducto" id="cantidadproducto" min="1" required onchange="calcularTotal();"></td>
            </tr>
            <tr>
                <td><b>Precio Unitario:</b></td>
                <td>$ <input type="text" name="precioventaproducto" id="precioventaproducto" readonly></td>
            </tr>
            <tr>
                <td><b>Anticipo:</b></td>
                <td>$ <input type="text" name="anticipo" id="anticipo" readonly></td>
            </tr>
            <tr>
                <td><b>Descripción:</b></td>
                <td><textarea name="descripcion" cols="50" rows="3" required></textarea></td>
            </tr>
            <tr>
                <td><b>Fecha Pedido:</b></td>
                <td><input type="date" name="fecha_pedido" required value="<?= date('Y-m-d'); ?>"></td>
            </tr>
            <tr>
                <td><b>Fecha de Entrega:</b></td>
                <td><input type="date" name="fecha_entrega" required value="<?= date('Y-m-d', strtotime('+7 days')); ?>"></td>
            </tr>
            <tr>
                <td><b>Total:</b></td>
                <td>$ <input type="text" name="total" id="total" readonly></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="hidden" name="ag" value="1">
                    <input type="submit" name="enviar" value="Procesar">
                    <input type="reset" value="Restablecer">
                    <input type="button" value="Cancelar" onclick="window.location.href='sistema.php';">
                </td>
            </tr>
        </table>
    </form>

    <?php include("plus/bottom.lm"); ?>
    
    <script>
        document.getElementById("idproducto").addEventListener("change", function() {
            let selectedOption = this.options[this.selectedIndex];
            document.getElementById("precioventaproducto").value = selectedOption.getAttribute("data-precio");
            calcularTotal();
        });
    </script>
</body>
</html>
