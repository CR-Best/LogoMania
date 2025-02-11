<?php 
include("plus/conexion.lm");
$idcliente=$_GET["id"];
$comp="SELECT * FROM clientes WHERE idcliente=\"$idcliente\"";
		$eje=mysql_query($comp,$conectar) or die("Error en la consulta, consulte a su administrador de Sistema");
		if(mysql_num_rows($eje)>=1)
		{
			$eliminar="DELETE FROM clientes WHERE idcliente=\"$idcliente\"";
			$eje=mysql_query($eliminar,$conectar) or die("Error en la consulta, consulte a su administrador de Sistema");
			$eliminar="DELETE FROM documentos_ccf WHERE idcliente=\"$idcliente\"";
			$eje=mysql_query($eliminar,$conectar) or die("Error en la consulta, consulte a su administrador de Sistema");
						$eliminar="DELETE FROM documentos_cf WHERE idcliente=\"$idcliente\"";
			$eje=mysql_query($eliminar,$conectar) or die("Error en la consulta, consulte a su administrador de Sistema");

			header("location:sistema.php?msg=cb");
		}
		
("location:sistema.php?msg=er");
?>