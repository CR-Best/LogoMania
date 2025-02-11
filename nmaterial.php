<?php 
include("plus/conexion.lm");
	session_start();

if(!isset($_SESSION["user"]))
	header("location:index.php");

$fmod=0;


if(isset($_GET["id"]))
	$doc=$_GET["id"];

if(isset($_GET["accion"]))
{
	if($_GET["accion"]=="eli")
	{
		
	
	$idprod=$_GET["idmaterial"];
$comp="DELETE FROM material WHERE idmaterial=\"$idprod\"";
		$eje=mysql_query($comp,$conectar) or die("Error en la consulta, consulte a su administrador de Sistema");

	
	}

	if ($_GET["accion"]=="mod")
	{
		$modi=mysql_query("SELECT * FROM material WHERE idmaterial=\"$_GET[idmaterial]\"",$conectar);
		
		if(mysql_num_rows($modi)==0)
		{
			header("location:nmaterial.php?idmaterial=$_GET[idmaterial]");
		
		}else
		{
			while($mo=mysql_fetch_array($modi))
			{
				$nombrematerial=$mo["nombrematerial"];
				$medidamaterial=$mo["medidamaterial"];
			
			}
			$fmod=1;
		
		}
	}


}	
	
if(isset($_POST["agregar"]))
{
	$doc=$_POST["idcliente"];

	$numero=1000;
	$comp=1;
	$flag=1;
	while($flag==1)
	{
		$numero++;
		$idproducto=$_POST["idmaterial"].$numero;
		$sql = "SELECT * FROM material WHERE idmaterial = \"$idproducto\""; 
		$ejecutar=mysql_query($sql,$conectar)or die("error en consulta");
	
		if(mysql_num_rows($ejecutar)<=0)
		{
			$flag=2;
		}
	}
	
	
	$sql = "INSERT INTO material VALUES (\"$idproducto\", \"$_POST[nombrematerial]\",  \"$_POST[medidamaterial]\")"; 
	$ejecutar=mysql_query($sql,$conectar);
	if($ejecutar)
		$comp=0;
		
		$url="inventrada.php?doc=$doc&&idmaterial=$idproducto";


}




if(isset($_POST["modificar"]))
{
	$idcliente=$_POST["idcliente"];

	
	$sql = "UPDATE material SET nombrematerial=\"$_POST[nombrematerial]\",  medidamaterial=\"$_POST[medidamaterial]\" WHERE idmaterial= \"$_POST[idmaterial]\""; 
	$ejecutar=mysql_query($sql,$conectar);
	if($ejecutar)
		header("location:nmaterial.php?id=$doc");


}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>...::: LOGOMATERIALES :::...</title>
<link href="plus/estilo.css" rel="stylesheet" type="text/css" />



<script language="javascript">
function redi(pagredi)
{
	var as=pagredi
	opener.location=as;
	self.close();


}

function elimi(vinculo)
{
	vinculo=vinculo;
	var preg="Esta seguro que desea eliminar este material?";
	var answer = confirm(preg);
	if (answer){
		alert("El material ha sido eliminado");
		window.location = vinculo;
	}
	else{
		alert("Accion cancelada!");
	}


}

</script>

</head>

<body <?php if (isset($_POST["agregar"])) echo "onLoad=\"redi('$url');\""; ?>>
<form action="nmaterial.php" method="post" name="producto">
<table width="100%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
        <tr>
          <td colspan="2" class="titulo">SISTEMA DE MATERIALES </td>
        </tr>
        <tr>
          <td width="50%" bgcolor="#FFFFFF">Nombre del Material <br />
		  <br /></td>
          <td valign="top" bgcolor="#FFFFFF"><input name="nombrematerial" type="text" id="nombrematerial" size="40" <?php 
		  	if ($fmod==1) echo "value=\"$nombrematerial\"";
		   ?>/>
          <input name="idcliente" type="hidden" id="idcliente" value="<?php echo $doc; ?>" />
          <br /> 
          Codigo: 
          <input name="idmaterial" type="text" id="idmaterial" size="6" maxlength="2" <?php 
		  	if ($fmod==1) echo "value=\"$_GET[idmaterial]\" readonly";
		   ?>/></td>
        </tr>
        <tr>
          <td bgcolor="#FFFFFF">Medida de Material : </td>
          <td valign="top" bgcolor="#FFFFFF"><input name="medidamaterial" type="text" id="medidamaterial" <?php 
		  	if ($fmod==1) echo "value=\"$medidamaterial\"";
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
          <td colspan="2" bgcolor="#FFFFFF" class="titulo">MATERIALES EXISTENTES </td>
        </tr>
        <tr>
          <td colspan="2" bgcolor="#FFFFFF"><table width="95%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="titulo">Material</td>
              <td class="titulo">Existencia</td>
              <td class="titulo">Costo Promedio</td>
              <td class="titulo">Modificar</td>
			                <td class="titulo">Eliminar</td>

            </tr>
			<?php
				$productos=mysql_query("SELECT * FROM material ORDER BY nombrematerial ASC", $conectar);
				while ($res=mysql_fetch_array($productos))
				{
					echo "<tr>
              <td>$res[nombrematerial]</td>";
			  
			  	$inv=mysql_query("SELECT * FROM inventario WHERE idmaterial=\"$res[idmaterial]\"",$conectar);
					$co=0;
					$ca=0;

				while($resinv=mysql_fetch_array($inv))
				{
					$co=$resinv["costomaterial"];
					$ca=$resinv["cantidad_material"];
				}
			  
			  
              echo "<td align=center>$ca $res[medidamaterial]</td>
			  <td align=center>$co</td>
              <td align=center><a href=nmaterial.php?idmaterial=$res[idmaterial]&accion=mod&id=$doc><img src=img/modificar.png border=0></a></td>
              <td align=center><a onclick=\"elimi('nmaterial.php?idmaterial=$res[idmaterial]&&accion=eli&&id=$doc')\"><img src=img/eliminar.png border=0></a></td>
            </tr>";
				
				}
			
			?>

          </table></td>
        </tr>
</table>
</form>

</body>
</html>
