<?php 
include("plus/conexion.lm");
session_start();

if(!isset($_SESSION["user"]))
	header("location:index.php");


$fechapedido=$_POST["apedido"]."/".$_POST["mpedido"]."/".$_POST["dpedido"];

$fechaentrega=$_POST["aentrega"]."/".$_POST["mentrega"]."/".$_POST["dentrega"];
$idpedido=100;
$flag=1;
while($flag==1)
{
	$idpedido++;
	$consulta=mysql_query("SELECT * FROM pedidos WHERE idpedido=\"$idpedido\" AND 		idcliente=\"$_POST[idclientes]\"", $conectar);

	if(mysql_num_rows($consulta)==0)
		$flag=2;
}

$sql = "INSERT INTO pedidos VALUES (\"$idpedido\", \"$_POST[idclientes]\", \"$fechapedido\",  \"$fechaentrega\", \"$_POST[idproducto]\", \"$_POST[descripcion]\", \"$_POST[precioventaproducto]\", \"$_POST[anticipo]\", \"$_POST[cantidadproducto]\", \"1\", \"$_SESSION[user]\")"; 
$ejecutar=mysql_query($sql,$conectar);
if($ejecutar)
{
	if($_POST["ag"]==2)
	{
	header("location:npedido.php?id=$_POST[idclientes]&msg=ok");
	}
	else
	{
	header("location:vpedido.php?cri=1");
	}
}

else 
("location:sistema.php?msg=er");
?>