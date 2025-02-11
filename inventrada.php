<?php 
include("plus/conexion.lm");

if(isset($_POST["finalizar"]))
{
$fechaentrada=$_POST["year"]."/".$_POST["mes"]."/".$_POST["dia"];

	$flage=0;

$cons=mysql_query("SELECT * FROM inventario WHERE idmaterial=\"$_POST[idmaterial]\"",$conectar);
if(mysql_num_rows($cons)==0)
	$costo=$_POST["preciounitario"];
else
{
	$flage=1;
	while($rec=mysql_fetch_array($cons))
	{
		$cantidad_material=$rec["cantidad_material"];
		$cant=$cantidad_material+$_POST["cantidad_material"];

		$costo=(($_POST["preciounitario"]*$_POST["cantidad_material"])+($rec["costomaterial"]*$rec["cantidad_material"]))/$cant;
		$costo=round($costo * 100) / 100;
	}
}



$sql = "INSERT INTO entradas_inventario VALUES (\"$fechaentrada\", \"$_POST[num]\", \"$_POST[idmaterial]\", \"$_POST[cantidad_material]\", \"$_POST[preciounitario]\")"; 
$ejecutar=mysql_query($sql,$conectar);


if($flage==1)
{
	$sent="UPDATE inventario SET cantidad_material=\"$cant\", costomaterial=\"$costo\" WHERE idmaterial=\"$_POST[idmaterial]\"";
$ejecutar=mysql_query($sent,$conectar) or die($sent);

}
else
{
	$sent = "INSERT INTO inventario VALUES (\"$_POST[idmaterial]\", \"$_POST[cantidad_material]\", \"$_POST[preciounitario]\")"; 
	$ejecutar=mysql_query($sent,$conectar);
}

if($ejecutar)
{
	if($_POST["ag"]==2)
	{
	header("location:inventrada.php?idmaterial=$_POST[idmaterial]&doc=$_POST[num]&msg=ok");
	}
	else
	{
	header("location:existencias.php");
	}
}

else 
("location:sistema.php?msg=er");






}



include("plus/header.lm");
?>
<script language="javascript">
function agregar()
{
	var answer = confirm("Decea hacer otro ingreso con mismo documento?");
	if (answer){
		document.inventrada.ag.value="2";
	}
}


function nv(pagina)
{
	var int_windowLeft = (screen.width - 600) / 2;
  var int_windowTop = (screen.height - 400) / 2;
if(document.inventrada.idmaterial.value==1)
{
	var conca=pagina+'?id='+document.inventrada.num.value;
window.open(conca,'inventario', 'left=' + int_windowLeft +',top=' + int_windowTop +', width=600, height=400,toolbar=0,resizable=0, scrollbars=1');
}
}


</script>
<?php

include("plus/top.lm");


?>

<form action="inventrada.php" method="post" name="inventrada">

<table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
  <tr>
    <td colspan="2" class="titulo"><img src="img/inventrada.jpg" width="300" height="75" /></td>
  </tr>
  <tr>
    <td width="24%"><b>Numero de Documento : </b></td>
    <td width="76%"><input name="num" type="text" id="num" size="10" <?php if (isset($_GET["doc"])) echo "value=\"$_GET[doc]\" readonly"; ?>></td>
  </tr>
  <tr>
    <td width="24%"><b>Material o Producto: </b></td>
    <td width="76%"><select name="idmaterial" id="idmaterial" <?php 
			
			
			echo "onchange=nv('nmaterial.php')"; ?>>
            <?php
			$idmaterial="";
			if(isset($_GET["idmaterial"]))
				$idmaterial=$_GET["idmaterial"];
				
			$sql=mysql_query("SELECT idmaterial, nombrematerial FROM material ORDER BY nombrematerial ASC", $conectar);
			while($nombremateriales=mysql_fetch_array($sql))
			{
				echo "<option value=$nombremateriales[idmaterial]";
				
				if ($nombremateriales[0]==$idmaterial)
					echo " selected";
					
				echo ">$nombremateriales[1]</option>";
			}
				echo "<option value=0>Seleccione un material</option>";

				echo "<option value=1>Agregar nuevo material</option>";
			?>
			
			
            </select>      </td>
  </tr>
  <tr>
    <td width="24%"><b>Precio Unitario : </b></td>
    <td width="76%">$ 
      <input name="preciounitario" type="text" id="preciounitario" size="10" /></td>
  </tr>
    <tr>
    <td width="24%"><b>Fecha de Compra : </b></td>
    <td width="76%">
	<select name="dpedido" id="dpedido">
      <?php
	  for($i=1;$i<=31;$i++)
	  {
	  	echo "<option value=$i ";
		if (date("d")==$i)
			echo "selected";
			
		echo ">$i";
	 	 echo "</option>";
	  
	  }
	  
	  ?>
        </select>
    -
      <select name="mes" id="mes"><?php
	  for($i=1;$i<=12;$i++)
	  {
	  	echo "<option value=$i ";
		if (date("m")==$i)
			echo "selected";
			
		echo ">";
	  	switch($i)
		{
			case 1: echo "Enero"; break;
			case 2: echo "Febrero"; break;
			case 3: echo "Marzo"; break;
			case 4: echo "Abril"; break;
			case 5: echo "Mayo"; break;
			case 6: echo "Junio"; break;
			case 7: echo "Julio"; break;
			case 8: echo "Agosto"; break;
			case 9: echo "Septiembre"; break;
			case 10: echo "Octubre"; break;
			case 11: echo "Noviembre"; break;
			case 12: echo "Diciembre"; break;
		}
	 	 echo "</option>";
	  
	  }
	  
	  ?>
        
      </select>
      -
      <select name="year" id="year">
	  	<?php
		
		$ye=date("Y");
			for($a=0; $a<=1; $a++)
			{
				$ye=$ye+$a;
				
				echo "<option value=$ye";
				if ($ye==date("Y"))
					echo " selected";
					
				echo ">$ye</option>";
			
			}
		
		?>
      </select>
	
	
	
	
	
	
	</td>
  </tr>
  
  <tr>
    <td width="24%"><b>Cantidad: </b></td>
    <td width="76%"><input name="cantidad_material" type="text" id="cantidad_material" size="10" /></td>
  </tr>
  <tr>
    <td colspan="2" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input name="ag" type="hidden" value="1" /><input type="submit" name="finalizar" value="Finalizar"   onclick="agregar();"/>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="button" name="Submit2" onClick="javascript:window.location.replace('existencias.php');" value="Cancelar" /></td></tr>
</table></form>
<?php 
	  include("plus/bottom.lm");
	  ?>
	