<?php 
include("plus/conexion.lm");

if(isset($_POST["enviar"]))
{
	$con=mysql_query("SELECT * FROM pedidos WHERE idpedido=\"$_POST[idpedido]\"",$conectar) or die("Error al buscar");
	$roro=mysql_fetch_array($con);
	if(mysql_num_rows($con)>0)
	{
		$anticipo=$_POST["anticipo"]+$roro[7];
		$fechaentrega=$_POST["aentrega"]."/".$_POST["mentrega"]."/".$_POST["dentrega"];
		$q="UPDATE pedidos SET fechaentrega=\"$fechaentrega\", descripcion=\"$_POST[descripcion]\", precio=\"$_POST[precio]\",
		cantidadproducto=\"$_POST[cantidadproducto]\", anticipo=\"$anticipo\" WHERE idpedido=\"$_POST[idpedido]\"";
		$mod=mysql_query($q,$conectar) or die("Error al actualizar");
		
		header("location:vpedido.php");
	}
}



if (isset($_GET["idpedido"]))
{
	$bus=mysql_query("SELECT * FROM pedidos WHERE idpedido=\"$_GET[idpedido]\"",$conectar);
	
	if(mysql_num_rows($bus)>0)
	{
		$recibo=mysql_fetch_row($bus);
		$idcliente=$recibo[1];
		$fechapedido=$recibo[2];
		$fechaentrega=$recibo[3];
		$idproducto=$recibo[4];
		$descripcion=$recibo[5];
		$precio=$recibo[6];
		$anticipo=$recibo[7];
		$cantidadproducto=$recibo[8];
		$estadopedido=$recibo[9];
	}
	else
	{
	
		header("location:vpedido.php");
	}



}else
{
header("location:vpedido.php");


}
include("plus/header.lm");

echo "<script language=\"javascript\">

anticipoanterior=$anticipo;
</script>";
?>


<script language="javascript">
function promtotal()
{
	document.pedidos.total.value=document.pedidos.cantidadproducto.value*document.pedidos.precio.value;
		var su=(((document.pedidos.cantidadproducto.value*document.pedidos.precio.value)/2)-anticipoanterior);



		if(su>0)
		document.pedidos.anticipo.value=su;
		else
		document.pedidos.anticipo.value=0;

}	 

function promtotal1()
{
	document.pedidos.total.value=document.pedidos.cantidadproducto.value*document.pedidos.precio.value;
		
		document.pedidos.anticipo.value=(((document.pedidos.cantidadproducto.value*document.pedidos.precio.value)/2));

}	 


function nv(pagina)
{
	var int_windowLeft = (screen.width - 600) / 2;
  var int_windowTop = (screen.height - 400) / 2;
if(document.pedidos.idproducto.value==1)
{
	var conca=pagina+'?id='+document.pedidos.idclientes.value;
window.open(conca,'principal', 'left=' + int_windowLeft +',top=' + int_windowTop +', width=600, height=400,toolbar=0,resizable=0, scrollbars=1');
}



}
function agregar()
{
	var answer = confirm("Desea agregar otra orden a este cliente?");
	if (answer){
		document.pedidos.ag.value="2";
	}
}

</script>


<?php
include("plus/top.lm");



$mensaje="";
if(isset($_GET["msg"]))
	$mensaje="<marquee><span class=mensajes align=center>Pedido Agregado Satisfactoriamente. </span></marquee><br><br>";
	
	
	echo $mensaje;
?>
<body onload="promtotal1();">
<form action="mpedido.php" method="post" name="pedidos">
<table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">

	    <tr>
          <td colspan="2" class="titulo"><img src="img/npedido.jpg" width="300" height="75" /></td>
          </tr>
		   
         <tr> <td width="24%"><b>Nombre del Cliente: </b></td>
          <td width="76%">
            <?php
				
			$sql=mysql_query("SELECT idcliente, nombrecliente FROM clientes WHERE idcliente=\"$idcliente\"", $conectar);
			while($nombreclientes=mysql_fetch_array($sql))
			{
				echo "<input name=\"idpedido\" type=\"hidden\" id=\"idpedido\" value=\"$_GET[idpedido]\">
				<input name=\"nombre\" size=50 type=\"text\" id=\"nombre\" value=\"$nombreclientes[nombrecliente]\" readonly>
				<input name=\"idcliente\" size=50 type=\"hidden\" id=\"idcliente\" value=\"$nombreclientes[idcliente]\">";
			}
			
			?>			</td>
        </tr>
		
		
		<tr> <td width="24%"><b>Producto: </b></td>
          <td width="76%">
		  
		              <?php

			$sql=mysql_query("SELECT idproducto, nombreproducto, precioventaproducto FROM productos WHERE idproducto=\"$idproducto\"", $conectar);
			while($nombreproductos=mysql_fetch_array($sql))
			{
				echo "<input name=\"producto\" size=50 type=\"text\" id=\"producto\" value=\"$nombreproductos[1]\" readonly>
				<input name=\"idproducto\" size=50 type=\"hidden\" id=\"idproducto\" value=\"$nombreproductos[0]\" readonly>";
				
				
			}
			?>
			
            </a> </td>
        </tr>
				<tr> <td width="24%"><b>Cantidad: 
				  
				</b></td>
          <td width="76%"><input name="cantidadproducto" type="text" id="cantidadproducto" onchange="promtotal();" <?php echo "value=$cantidadproducto"; ?>>          </td>
        </tr>
		        
				<tr> <td width="24%"><b>Precio Unitario: </b></td>
                  <td width="76%">$ <input name="precio" type="text" id="precio"  onchange="promtotal();" <?php echo "value=".number_format($precio, 2, '.', '').""; ?>>          </td>
        </tr>
		
						<tr> <td width="24%"><b>Anticipo: </b></td>
                          <td width="76%">$ <input name="anticipo" type="text" id="anticipo"  <?php echo "value=".number_format($anticipo, 2, '.', '').""; ?>>
                            <input name="anticipo2" type="hidden" id="anticipo2"></td>
        </tr>
		<tr> <td width="24%"><b>Descripci&oacute;n: </b></td>
          <td width="76%"><textarea name="descripcion" cols="50" rows="3" id="descripcion"><?php echo "$descripcion"; ?></textarea></td>
        </tr>
		
						<tr> <td width="24%"><b>Fecha de entrega: </b></td>
                  <td width="76%">
				  
				  
				<select name="dentrega" id="dentrega">
      <?php
	  
			list($a,$m,$d)=split("-",$fechaentrega);
	  for($i=1;$i<=31;$i++)
	  {
	  	echo "<option value=$i ";
		if ($d==$i)
			echo "selected";
			
		echo ">$i";
	 	 echo "</option>";
	  
	  }
	  
	  ?>
                </select>
    -
      <select name="mentrega" id="mentrega"><?php
	  for($i=1;$i<=12;$i++)
	  {
	  	echo "<option value=$i ";
		if ($m==$i)
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
      <select name="aentrega" id="aentrega">
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
      </select>				  </td>
        </tr>
		<tr> <td width="24%"><b>Total: </b></td>
                  <td width="76%">$ 
                    <input name="total" type="text" id="total" onfocus="promtotal();">          </td>
        </tr>
		 
		 <tr>
		   <td colspan="2" align="center">
		     <input name="enviar" type="submit" id="enviar" value="Procesar"> 
		     &nbsp;&nbsp;&nbsp;
		     <input type="reset" name="Submit" value="Restablecer" />		     
		     &nbsp;&nbsp;&nbsp;&nbsp; <input type="submit" name="Submit2" value="Cancelar" /></td>
		   </tr>
	  </table>    
</form>








      <?php 
	  include("plus/bottom.lm");
	  ?>
	