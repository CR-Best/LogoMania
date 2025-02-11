<?php 
include("plus/conexion.lm");

if(isset($_POST["idmaterial"]) && $_POST["idmaterial"]!="1" && !isset($_POST["ag"]))
{
	header("location:invsalida.php?idmaterial=$_POST[idmaterial]");


}


if(isset($_POST["ag"]))
{
	$idmaterial=$_POST["idmaterial"];
	$sql = "UPDATE inventario SET cantidad_material=\"".($_POST["cantidadactual"]-$_POST["cantidad_material"])."\" WHERE idmaterial=\"$idmaterial\""; 
	$buscar=mysql_query($sql,$conectar)or die("error en consulta");
	
		$sql = "SELECT * FROM inventario WHERE idmaterial=\"$idmaterial\""; 
	$buscar=mysql_query($sql,$conectar)or die("error en consulta");
	$resul=mysql_fetch_row($buscar);
	

	
	$sql = "INSERT INTO salidas_inventario VALUES(\"".date("Y-m-d")."\", \"$idmaterial\", \"$_POST[cantidad_material]\", \"$resul[2]\")"; 
	$buscar=mysql_query($sql,$conectar)or die("error en consulta");
	
	if($_POST["ag"]==2)
		header("location:invsalida.php");
	else
		header("location:existencias.php");
		  


}




include("plus/header.lm");
?>
<script language="javascript">
function vac()
{



	if (document.invsalida.cantidad_material.value=="")
	{
	
		document.invsalida.cantidad_material.focus();
		alert("Ingrese la cantidad que desee registrar por favor!");
		return false;

	}else
	{	if(parseInt(document.invsalida.cantidad_material.value) > parseInt(document.invsalida.cantidadactual.value))
		{
			document.invsalida.cantidad_material.focus();
			alert("No existen el material suficiente para suplir esta orden! Material Disponible: "+document.invsalida.cantidadactual.value+ ". Material requerido: "+document.invsalida.cantidad_material.value);
			return false;
		
		}
		else
		{

			var answer = confirm("Desea continuar en Salidas de Inventarios?");
			if (answer)
			{
				document.invsalida.ag.value="2";
			}
		}
	}
}




</script>
<?php

include("plus/top.lm");


?>





<?php
if(!isset($_GET["idmaterial"]))
{
?>
          <form action="invsalida.php" method="post" name="salida"><table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
              
		<tr>
          <td colspan="2" class="titulo"><img src="img/invsalida.jpg" width="300" height="75" /></td>
          </tr>
		   
          <td width="24%"><b>Nombre de Material: </b></td>
          <td width="76%"><input name="busqueda" type="text" size="50" /><br />
<select name="idmaterial" id="idmaterial">
            <?php
			
			echo "<option value=1>Seleccione un producto de la lista</option>";

			$sql=mysql_query("SELECT idmaterial, nombrematerial FROM material ORDER BY nombrematerial ASC", $conectar);
			while($nombremateriales=mysql_fetch_array($sql))
			{
				echo "<option value=$nombremateriales[idmaterial]";
				echo ">$nombremateriales[1]</option>";
			}
			?>
			
			
            </select>     
</td>
        </tr>
		 <tr>
		   <td colspan="2" align="center"><input type="submit" name="buscar" value="Buscar" />





		     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="submit" name="Submit2" value="Cancelar" />
			 </td>
		   </tr>
		   
      </table></form>
<?php 
}
?>

<?php
if(isset($_POST["busqueda"]))
{
?>

<table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
              
		   <?php
		   	$criterio=$_POST["busqueda"];
	$sql = "SELECT * FROM material WHERE nombrematerial LIKE \"%$criterio%\""; 
	$buscar=mysql_query($sql,$conectar)or die("error en consulta");
	$total=mysql_num_rows($buscar);
	echo "<tr><td colspan=5 class=titulo>Registros encontrados: <b>$total</b></td></tr>";
		   ?>
          <tr>
            <td class=titulo><b>Nombre del Material</b></td>
            <td align="center" class=titulo>Costo Promedio</td>
            <td align="center" class=titulo>Existencias</td>
          </tr>
		  <?php
		  
		  


		  
		  while($res=mysql_fetch_array($buscar))
		  {
			$bus="SELECT * FROM inventario WHERE idmaterial=\"$res[0]\"";
			$consulta=mysql_query($bus, $conectar) or die("Error");
			if(mysql_num_rows($consulta)>=1)
				$row = mysql_fetch_row($consulta);
			else
			{
				$row[1]=0;
				$row[2]=0;
			
			}
		  echo "<tr>
            <td>";
			if($row[1]>0)
				echo "<a href=invsalida.php?idmaterial=$res[0]>$res[1]</a>";
			else
				echo "$res[1]";

			echo"</td>
            <td align=center>$ ".number_format($row[2], 2, '.', '')."</a></td>
            <td align=center>$row[1] $res[medidamaterial]</td>
			
          </tr>";
		  }
		  echo "</table>";
		  ?>

<?php
}


?>


<?php

if(isset($_GET["idmaterial"]))
{




?>

<?php
	$idmaterial=$_GET["idmaterial"];
	$sql = "SELECT * FROM material WHERE idmaterial=\"$idmaterial\""; 
	$buscar=mysql_query($sql,$conectar)or die("error en consulta");
	$total=mysql_num_rows($buscar);
		  


		  
		  while($res=mysql_fetch_array($buscar))
		  {
			$bus="SELECT * FROM inventario WHERE idmaterial=\"$idmaterial\"";
			$consulta=mysql_query($bus, $conectar) or die("Error");
			if(mysql_num_rows($consulta)>=1)
				$row = mysql_fetch_row($consulta);
			else
			{
				$row[1]=0;
				$row[2]=0;
			
			}
			$nombre=$res["nombrematerial"];
		}


?>
<form action="invsalida.php" method="post" name="invsalida"  onSubmit="return vac();">
<table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
  <tr>
    <td colspan="2" class="titulo"><img src="img/invsalida.jpg" width="300" height="75" /></td>
  </tr>
  <tr>
    <td width="24%"><b>Nombre del Material: </b></td>
    <td width="76%"><input name="nombrematerial" type="text" id="nombrematerial" size="40" <?php echo "value=\"$nombre\""; ?> readonly>
    <input name="idmaterial" type="hidden" id="idmaterial" <?php echo "value=\"$idmaterial\""; ?>></td>
  </tr>
  <tr>
    <td width="24%"><b>Cantidad a utilizar: </b></td>
    <td width="76%"><input name="cantidad_material" type="text" id="cantidad_material" size="2"/>
    <input name="cantidadactual" type="hidden" id="cantidadactual" <?php echo "value=\"$row[1]\""; ?>></td>
  </tr>
  <tr>
    <td colspan="2" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input name="ag" type="hidden" value="1" /><input type="submit" name="finalizar" value="Finalizar">
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="button" name="Submit2" onClick="javascript:window.location.replace('existencias.php');" value="Cancelar" /></td></tr>
</table>

</form>

<?php

}
?>

<?php 
	  include("plus/bottom.lm");
	  ?>
	