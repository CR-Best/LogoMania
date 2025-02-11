<?php 
include("plus/conexion.lm");
session_start();

if(!isset($_SESSION["user"]))
	header("location:index.php");



$sql="UPDATE clientes SET nombrecliente=\"$_POST[nombrecliente]\",  dircliente=\"$_POST[dircliente]\", telcliente=\"$_POST[telcliente]\", cellcliente=\"$_POST[cellcliente]\", faxcliente=\"$_POST[faxcliente]\", emailcliente=\"$_POST[emailcliente]\", clasecliente=\"$_POST[clasecliente]\", tipodocumento=\"$_POST[tipodocumento]\" WHERE idcliente=\"$_POST[idcliente]\"";


$ejecutar=mysql_query($sql,$conectar);
if($ejecutar)
{
	if($_POST["tipodocumento"]==1)
	{
		$comp="SELECT * FROM documentos_ccf WHERE idcliente=\"$_POST[idcliente]\"";
		$eje=mysql_query($comp,$conectar) or die("Error en la consulta, consulte a su administrador de Sistema");
		if(mysql_num_rows($eje)>=1)
		{
			$sql = "UPDATE documentos_ccf SET registrocliente=\"$_POST[registrocliente]\", girocliente=\"$_POST[girocliente]\"  WHERE idcliente=\"$_POST[idcliente]\""; 
			$ejecutar=mysql_query($sql,$conectar) or die("Error en la consulta, consulte a su administrador de Sistema");
		}
		else
		{
			$sql = "INSERT INTO documentos_ccf VALUES (\"$_POST[idcliente]\", \"$_POST[registrocliente]\",  \"$_POST[girocliente]\")"; 
			$ejecutar=mysql_query($sql,$conectar);	
		}
		
	
	}
	else
	{
		$comp="SELECT * FROM documentos_cf WHERE idcliente=\"$_POST[idcliente]\"";
		$eje=mysql_query($comp,$conectar) or die("Error en la consulta, consulte a su administrador de Sistema");
		if(mysql_num_rows($eje)>=1)
		{
			$sql = "UPDATE documentos_cf SET duicliente=\"$_POST[registrocliente]\", nitcliente=\"$_POST[girocliente]\"  WHERE idcliente=\"$_POST[idcliente]\""; 
			$ejecutar=mysql_query($sql,$conectar) or die("Error en la consulta, consulte a su administrador de Sistema");
		}
		else
		{
			$sql = "INSERT INTO documentos_cf VALUES (\"$_POST[idcliente]\", \"$_POST[registrocliente]\",  \"$_POST[girocliente]\")"; 
			$ejecutar=mysql_query($sql,$conectar);	
		}
		
	

	
	}
	header("location:sistema.php?msg=cac");
}

else 
("location:sistema.php?msg=er");
?>