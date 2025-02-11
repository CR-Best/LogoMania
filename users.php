<?php 
include("plus/conexion.lm");
	session_start();
	
	
if(isset($_POST["ac"]))
{
	if($_POST["ac"]=="nu")
	{
		$agregar=mysql_query("INSERT INTO users VALUES (\"$_POST[idusuario]\",\"$_POST[nombre]\",\"$_POST[pass1]\",\"$_POST[nivel]\")",$conectar);
		header("location:users.php?comp=1");
	
	
	}


		if($_POST["ac"]=="mod")
	{
			$pa="";
		if($_POST["pass1"]!="")
			$pa=" password=\"$_POST[pass1]\",";
		$texto="UPDATE users SET nombre=\"$_POST[nombre]\",$pa nivel=\"$_POST[nivel]\" WHERE idusuario=\"$_POST[idusuario]\"";
		$agregar=mysql_query($texto,$conectar);
		header("location:users.php?comp=2");
	
	
	}


		if($_POST["ac"]=="eli")
	{
		$agregar=mysql_query("DELETE FROM users WHERE idusuario=\"$_POST[idusuario]\"",$conectar);
		header("location:users.php?comp=3");
	
	
	}

}



$accion=0;
if(!isset($_SESSION["user"]))
	header("location:index.php");

if(isset($_POST["password"]))
{
	if($_POST["password"]!=$_SESSION["pass"])
		header("location:users.php?accion=$_POST[accion]&&id=$_POST[tipo]");
		
	$id=$_POST["tipo"];
	$accione=$_POST["accion"];
	
		if($accione=="nu")
		$accion=1;


	if($accione=="mod")
		$accion=2;

	if($accione=="eli")
		$accion=3;
		

		
}
if(isset($_GET["comp"]))
		$accion=4;


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>...::: LOGOUSUARIOS :::...</title>
<link href="plus/estilo.css" rel="stylesheet" type="text/css" />


<script language="javascript">


function tiempin()
{
timer = setTimeout("redi()", 3000);;


}
function redi()
{
	
	opener.location='sistema.php';
	self.close();


}

function elimi(vinculo)
{
	vinculo=vinculo+cliente;
	var preg="Esta seguro que desea eliminar este usuario?";
	var answer = confirm(preg);
	if (answer){
		alert("El usuario ha sido eliminado");
		window.location = vinculo;
	}
	else{
		alert("Accion cancelada!");
	}


}



function vac()
{
	
	if (document.usuarios.pass1.value==document.usuarios.pass2.value)
	{
		return true;
	}
	else
	{
		document.usuarios.pass1.focus();
		alert("Las contrasenas no coinciden!");
		return false;

	
	}

}	


</script>

</head>

<body <?php 
if(isset($_GET["comp"]))
echo "onload=tiempin()"; 
?>>
<?php
	if($accion==1)
	{
	
?>
	<form action="users.php" method="post" name="usuarios"  onclick="return vac();">
	<table width="100%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
		  <tr>
		    <td colspan="2" class="titulo">SISTEMA DE USUARIOS </td>
			</tr>
			<tr>
			  <td width="50%" bgcolor="#FFFFFF">Nombre del Usuario: <br />
			  <br /></td>
			  <td valign="top" bgcolor="#FFFFFF"><input name="nombre" type="text" id="nombre" size="40">
			  <br /> 
			  Codigo: 
			  <input name="idusuario" type="text" id="idusuario" size="20" >
			  <input name="ac" type="hidden" id="ac" value="<?php echo $accione; ?>" /></td>
			</tr>
		  <tr>
		    <td bgcolor="#FFFFFF">Password: </td>
			  <td valign="top" bgcolor="#FFFFFF"><input name="pass1" type="password" id="pass1"></td>
			</tr>
					<tr>
			  <td bgcolor="#FFFFFF">Confirme Password: </td>
			  <td valign="top" bgcolor="#FFFFFF"><input name="pass2" type="password" id="pass2"></td>
			</tr>
			<tr>
			  <td bgcolor="#FFFFFF">Nivel de Usuario : </td>
			  <td valign="top" bgcolor="#FFFFFF"><select name="nivel" id="nivel">
			    <option value="1">Administrador</option>
			    <option value="2" selected="selected">Usuario</option>
			    </select>
			  </td>
			</tr>
			<tr>
			  <td colspan="2" align="center" bgcolor="#FFFFFF"><input type="submit" value="Agregar">
			  &nbsp;&nbsp;&nbsp;&nbsp; <input type="submit" name="Submit2" value="Cerrar" onclick="javascript:window.close()"/></td>
			</tr>
	</table>
	</form>
	
	
	
	
<?php

}

	if($accion==2)
	{
		$usuario=mysql_query("SELECT * FROM users WHERE idusuario=\"$id\"",$conectar);
		$re=mysql_fetch_row($usuario);
		
		
?>
	<form action="users.php" method="post" name="usuarios" onclick="return vac();">
	<table width="100%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
		  <tr>
		    <td colspan="2" class="titulo">SISTEMA DE USUARIOS </td>
			</tr>
			<tr>
			  <td width="50%" bgcolor="#FFFFFF">Nombre del Usuario: <br />
			  <br /></td>
			  <td valign="top" bgcolor="#FFFFFF"><input name="nombre" type="text" id="nombre" size="40" 
			  <?php echo "value=\"$re[1]\""; ?>>
			  <br /> 
			  Codigo: 
			  <input name="idusuario" type="text" id="idusuario" size="20" <?php echo "value=\"$re[0]\""; ?> readonly>
			  <input name="ac" type="hidden" id="ac" value="<?php echo $accione; ?>" /></td>
			</tr>
		  <tr>
		    <td bgcolor="#FFFFFF">Password: </td>
			  <td valign="top" bgcolor="#FFFFFF"><input name="pass1" type="password" id="pass1"></td>
			</tr>
					<tr>
			  <td bgcolor="#FFFFFF">Confirme Password: </td>
			  <td valign="top" bgcolor="#FFFFFF"><input name="pass2" type="password" id="pass2"></td>
			</tr>
						<tr>
			  <td bgcolor="#FFFFFF">Nivel de Usuario : </td>
			  <td valign="top" bgcolor="#FFFFFF"><select name="nivel" id="nivel">
			    <option value="1" <?php if($re[3]==1) echo "selected"; ?>>Administrador</option>
			    <option value="2"  <?php if($re[3]==2) echo "selected"; ?>>Usuario</option>
			    </select>
			  </td>
			</tr>
			<tr>
			  <td colspan="2" align="center" bgcolor="#FFFFFF"><input type="submit" value="Modificar">
			  &nbsp;&nbsp;&nbsp;&nbsp; <input type="submit" name="Submit2" value="Cerrar" onclick="javascript:window.close()"/></td>
			</tr>
	</table>
	</form>
<?php
	}
?>





<?php


	if($accion==3)
	{
		$usuario=mysql_query("SELECT * FROM users WHERE idusuario=\"$id\"",$conectar);
		$re=mysql_fetch_row($usuario);
		
		
?>
	<form action="users.php" method="post" name="usuarios">
	<table width="100%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
		  <tr>
		    <td colspan="2" class="titulo">SISTEMA DE USUARIOS </td>
			</tr>
			<tr>
			  <td width="50%" bgcolor="#FFFFFF">Nombre del Usuario: <br />
			  <br /></td>
			  <td valign="top" bgcolor="#FFFFFF"><input name="nombre" type="text" id="nombre" size="40" 
			  <?php echo "value=\"$re[1]\""; ?>>
			  <br /> 
			  Codigo: 
			  <input name="idusuario" type="text" id="idusuario" size="20" <?php echo "value=\"$re[0]\""; ?> readonly>
			  <input name="ac" type="hidden" id="ac" value="<?php echo $accione; ?>" /></td>
			</tr>
			<tr>
			  <td colspan="2" align="center" bgcolor="#FFFFFF"><input type="submit" value="Eliminar">
			  &nbsp;&nbsp;&nbsp;&nbsp; <input type="submit" name="Submit2" value="Cerrar" onclick="javascript:window.close()"/></td>
			</tr>
	</table>
	</form>
<?php
	}
?>

<?php





if($accion==0)
{
?>

	<form action="users.php" method="post" name="usuarios">
	<table width="100%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F" bgcolor="#FFFFFF">
	<tr><td class=titulo>Compruebe cuenta:</td></tr><tr><td>Contrase&ntilde;a: <input name="password" type="password" size="20" /> <input name="accion" type="hidden" id="accion" <?php echo "value=$_GET[accion]"; ?>>
	<input name="tipo" type="hidden" id="tipo" <?php echo "value=$_GET[id]"; ?>>
	<input name="comprobacion" type="submit" id="comprobacion" value="Comprobar" /> <input type="submit" name="Submit22" value="Cerrar" onclick="javascript:window.close()"/></td></tr></table></form>



<?php
	}

?>


<?php





if($accion==4)
{
?>

	<table width="100%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F" bgcolor="#FFFFFF">
	<tr>
	  <td class=titulo>SISTEMA DE USUARIOS :</td>
	</tr><tr><td><?php
	
	if($_GET["comp"]==1)
		echo "Usuario Agregado satisfactoriamente";

	if($_GET["comp"]==2)
		echo "Usuario Modificado satisfactoriamente";
		
	if($_GET["comp"]==3)
		echo "Usuario Eliminado satisfactoriamente";
		
		
		echo "<br>Esta ventana se cerrar&aacute; autom&aacute;ticamente en 3 segundos";
	?></td>
	</tr></table>



<?php
	}

?>

</body>
</html>
