<?php 
include("plus/conexion.lm");
	session_start();

if(!isset($_SESSION["user"]))
	header("location:index.php");

$fmod=0;


if(isset($_GET["id"]))
	$idcliente=$_GET["id"];

if(isset($_GET["accion"]))
{
	if($_GET["accion"]=="eli")
	{
		
	
	$idprod=$_GET["idproducto"];
$comp="DELETE FROM productos WHERE idproducto=\"$idprod\"";
		$eje=mysql_query($comp,$conectar) or die("Error en la consulta, consulte a su administrador de Sistema");

	
	}

	if ($_GET["accion"]=="mod")
	{
		$modi=mysql_query("SELECT * FROM productos WHERE idproducto=\"$_GET[idprod]\"",$conectar);
		
		if(mysql_num_rows($modi)==0)
		{
			header("location:nproducto.php?id=$_GET[id]");
		
		}else
		{
			while($mo=mysql_fetch_array($modi))
			{
				$nombreproducto=$mo["nombreproducto"];
				$preciocostoproducto=$mo["preciocostoproducto"];
				$precioventaproducto=$mo["precioventaproducto"];
			
			}
			$fmod=1;
		
		}
	}


}	
	
if(isset($_POST["agregar"]))
{
	$idcliente=$_POST["idcliente"];

	$numero=1000;
	$comp=1;
	$flag=1;
	while($flag==1)
	{
		$numero++;
		$idproducto=$_POST["idproducto"].$numero;
		$sql = "SELECT * FROM productos WHERE idproducto = \"$idproducto\""; 
		$ejecutar=mysql_query($sql,$conectar)or die("error en consulta");
	
		if(mysql_num_rows($ejecutar)<=0)
		{
			$flag=2;
		}
	}
	
	
	$sql = "INSERT INTO productos VALUES (\"$idproducto\", \"$_POST[nombreproducto]\",  \"$_POST[preciocosto]\", \"$_POST[precioventa]\")"; 
	$ejecutar=mysql_query($sql,$conectar);
	if($ejecutar)
		$comp=0;
		
		$url="npedido.php?id=$idcliente&&idprod=$idproducto";


}




if(isset($_POST["modificar"]))
{
	$idcliente=$_POST["idcliente"];

	
	$sql = "UPDATE productos SET nombreproducto=\"$_POST[nombreproducto]\",  preciocostoproducto=\"$_POST[preciocosto]\", precioventaproducto=\"$_POST[precioventa]\" WHERE idproducto= \"$_POST[idproducto]\""; 
	$ejecutar=mysql_query($sql,$conectar);
	if($ejecutar)
		header("location:nproducto.php?id=$idcliente");


}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>...::: LOGOPRODUCTOS :::...</title>
<link href="plus/estilo.css" rel="stylesheet" type="text/css" />
<?php
echo "<script language=\"javascript\">";
echo "cliente='$_GET[id]';";
if(isset($_POST["agregar"]) || isset($_POST["modificar"]) )
echo "cliente='$_POST[idcliente]';";

echo "</script>";


?>


<script language="javascript">
function redi(pagredi)
{
	var as=pagredi
	opener.location=as;
	self.close();


}

function elimi(vinculo)
{
	vinculo=vinculo+cliente;
	var preg="Esta seguro que desea eliminar este producto?"+vinculo;
	var answer = confirm(preg);
	if (answer){
		alert("El producto ha sido eliminado");
		window.location = vinculo;
	}
	else{
		alert("Accion cancelada!");
	}


}

</script>

</head>

<body <?php if (isset($_POST["agregar"])) echo "onLoad=\"redi('$url');\""; ?>>
<form action="nproducto.php" method="post" name="producto">
<table width="100%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
        <tr>
          <td colspan="2" class="titulo">SISTEMA DE PRODUCTOS </td>
        </tr>
        <tr>
          <td width="50%" bgcolor="#FFFFFF">Nombre del Producto <br />
		  <br /></td>
          <td valign="top" bgcolor="#FFFFFF"><input name="nombreproducto" type="text" id="nombreproducto" size="40" <?php 
		  	if ($fmod==1) echo "value=\"$nombreproducto\"";
		   ?>/>
          <input name="idcliente" type="hidden" id="idcliente" value="<?php echo $idcliente; ?>" />
          <br /> 
          Codigo: 
          <input name="idproducto" type="text" id="idproducto" size="6" maxlength="2" <?php 
		  	if ($fmod==1) echo "value=\"$_GET[idprod]\" readonly";
		   ?>/></td>
        </tr>
        <tr>
          <td bgcolor="#FFFFFF">Precio de Costo: </td>
          <td valign="top" bgcolor="#FFFFFF">$ 
            <input name="preciocosto" type="text" id="preciocosto" <?php 
		  	if ($fmod==1) echo "value=\"$preciocostoproducto\"";
		   ?>></td>
        </tr>
        <tr>
          <td bgcolor="#FFFFFF">Precio de Venta:</td>
          <td valign="top" bgcolor="#FFFFFF">$ 
          <input name="precioventa" type="text" id="precioventa" <?php 
		  	if ($fmod==1) echo "value=\"$precioventaproducto\"";
		   ?>></td>
        </tr>
        <tr>
          <td colspan="2" align="center" bgcolor="#FFFFFF"><input type="submit" <?php 
		  	if ($fmod==1) echo "name=\"modificar\"  value=\"Modificar\"";
			else echo "name=\"agregar\"  value=\"Agregar\"";
		   ?>>
          &nbsp;&nbsp;&nbsp;&nbsp; <input type="submit" name="Submit2" value="Cerrar" onclick="javascript:window.close()"/></td>
        </tr>
        <tr>
          <td colspan="2" bgcolor="#FFFFFF" class="titulo">PRODUCTOS EXISTENTES </td>
        </tr>
        <tr>
          <td colspan="2" bgcolor="#FFFFFF"><table width="95%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="titulo">Producto</td>
              <td class="titulo">PC</td>
              <td class="titulo">PV</td>
              <td class="titulo">Modificar</td>
              <td class="titulo">Eliminar</td>
            </tr>
			<?php
				$productos=mysql_query("SELECT * FROM productos ORDER BY nombreproducto ASC", $conectar);
				while ($res=mysql_fetch_array($productos))
				{
					echo "<tr>
              <td>$res[nombreproducto]</td>
              <td align=center>$ $res[preciocostoproducto]</td>
              <td align=center>$ $res[precioventaproducto]</td>
              <td align=center><a href=nproducto.php?idprod=$res[idproducto]&accion=mod&id=$idcliente><img src=img/modificar.png border=0></a></td>
              <td align=center><a onclick=\"elimi('nproducto.php?idproducto=$res[idproducto]&&accion=eli&&id=')\"><img src=img/eliminar.png border=0></a></td>
            </tr>";
				
				}
			
			?>

          </table></td>
        </tr>
</table>
</form>

</body>
</html>
