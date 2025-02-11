<?php 
include("plus/conexion.lm");
session_start();

if(!isset($_SESSION["user"]))
	header("location:index.php");


$numero=1000;

$flag=1;
while($flag==1)
{
	$numero++;
	$idcliente=$_POST["idcliente"].$numero;
	$sql = "SELECT * FROM clientes WHERE idcliente = \"$idcliente\""; 
	$ejecutar=mysql_query($sql,$conectar)or die("error en consulta");

	if(mysql_num_rows($ejecutar)<=0)
	{
		$flag=2;
		
	
	}

}


$sql = "INSERT INTO clientes VALUES (\"$idcliente\", \"$_POST[nombrecliente]\",  \"$_POST[dircliente]\", \"$_POST[telcliente]\", \"$_POST[cellcliente]\", \"$_POST[faxcliente]\", \"$_POST[emailcliente]\", \"$_POST[clasecliente]\", \"$_POST[tipodocumento]\")"; 
$ejecutar=mysql_query($sql,$conectar);
if($ejecutar)
{
	if($_POST["tipodocumento"]==1)
	{
		$sql = "INSERT INTO documentos_ccf VALUES (\"$idcliente\", \"$_POST[registrocliente]\",  \"$_POST[girocliente]\")"; 
		$ejecutar=mysql_query($sql,$conectar);
	
	}
	else
	{
		$sql = "INSERT INTO documentos_cf VALUES (\"$idcliente\", \"$_POST[registrocliente]\",  \"$_POST[girocliente]\")"; 
		$ejecutar=mysql_query($sql,$conectar);
	}
	header("location:sistema.php?msg=ca");
}

else 
("location:sistema.php?msg=er");
?>