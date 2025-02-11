<?php 
include("plus/conexion.lm");
include("plus/header.lm");
?>


<script language="javascript">
function promtotal()
{
	document.pedidos.total.value=document.pedidos.cantidadproducto.value*document.pedidos.precioventaproducto.value;
		document.pedidos.anticipo.value=(document.pedidos.cantidadproducto.value*document.pedidos.precioventaproducto.value)/2;

}	 

function nv(pagina)
{
	var int_windowLeft = (screen.width - 600) / 2;
  var int_windowTop = (screen.height - 400) / 2;
if(document.pedidos.idproducto.value==1)
{
	var conca=pagina+'?id='+document.pedidos.idclientes.value;
window.open(conca,'pedido', 'left=' + int_windowLeft +',top=' + int_windowTop +', width=600, height=400,toolbar=0,resizable=0, scrollbars=1');
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

<form action="npedido1.php" method="post" name="pedidos">
<table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">

	    <tr>
          <td colspan="2" class="titulo"><img src="img/npedido.jpg" width="300" height="75" /></td>
          </tr>
		   
         <tr> <td width="24%"><b>Nombre del Cliente: </b></td>
          <td width="76%"><select name="idclientes" id="idclientes">
            <?php
			$idcliente="CR1001";
			if(isset($_GET["id"]))
				$idcliente=$_GET["id"];
				
			$sql=mysql_query("SELECT idcliente, nombrecliente FROM clientes ORDER BY nombrecliente ASC", $conectar);
			while($nombreclientes=mysql_fetch_array($sql))
			{
				echo "<option value=$nombreclientes[0] ";
				if ($nombreclientes[0]==$idcliente)
					echo "selected";
					
				echo ">$nombreclientes[1]</option>";
			}
			
			?>
			
			
			
            </select>          </label></td>
        </tr>
		
		
		<tr> <td width="24%"><b>Producto: </b></td>
          <td width="76%"><select name="idproducto" id="idproducto" <?php 
			
			
			echo "onchange=nv('nproducto.php')"; ?>>
		  
		              <?php
			$idprod="";
			if(isset($_GET["id"]))
				$idcliente=$_GET["id"];

				if(isset($_GET["idprod"]))
					$idprod=$_GET["idprod"];


			$sql=mysql_query("SELECT idproducto, nombreproducto, precioventaproducto FROM productos", $conectar);
			while($nombreproductos=mysql_fetch_array($sql))
			{
				echo "<option value=$nombreproductos[0]";
				
				
				if ($idprod == $nombreproductos[0])
					echo " selected";
					
				echo ">$nombreproductos[1] -$ $nombreproductos[2]</option>";
			}
			
			echo "<option value=0>Seleccione un producto</option>";

			if ($_SESSION["nivel"]==1)
				echo "<option value=1>Agregar nuevo producto</option>";
			?>
			
          </select>
            <a href="npedido.php#"></a> </td>
        </tr>
				<tr> <td width="24%"><b>Cantidad: 
				  
				</b></td>
          <td width="76%"><input name="cantidadproducto" type="text" id="cantidadproducto" onchange="promtotal();" />          </td>
        </tr>
		        
				<tr> <td width="24%"><b>Precio Unitario: </b></td>
                  <td width="76%">$ <input name="precioventaproducto" type="text" id="precioventaproducto"  onchange="promtotal();" />          </td>
        </tr>
		
						<tr> <td width="24%"><b>Anticipo: </b></td>
                          <td width="76%">$ <input name="anticipo" type="text" id="anticipo" />          </td>
        </tr>
		<tr> <td width="24%"><b>Descripci&oacute;n: </b></td>
          <td width="76%"><textarea name="descripcion" cols="50" rows="3" id="descripcion"></textarea></td>
        </tr>
		
		        
				<tr> <td width="24%"><b>Fecha Pedido: </b></td>
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
      <select name="mpedido" id="mpedido"><?php
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
      <select name="apedido" id="apedido">
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
		
						<tr> <td width="24%"><b>Fecha de entrega: </b></td>
                  <td width="76%">
				  
				  
				<select name="dentrega" id="dentrega">
      <?php
	  
	  $fech=mktime(0, 0, 0, date("m")  , date("d")+7, date("Y"));
			$comp=date("Y-m-d",$fech);
			list($a,$m,$d)=split("-",$comp);
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
      </select>  
				  
				  
				  
				  
				  
				  
				  
				  
				  </td>
        </tr>
		<tr> <td width="24%"><b>Total: </b></td>
                  <td width="76%">$ 
                    <input name="total" type="text" id="total" onfocus="promtotal();">          </td>
        </tr>
		 
		 <tr>
		   <td colspan="2" align="center"><input name="ag" type="hidden" id="ag" value="1" />
		     <input name="enviar" type="submit" id="enviar" value="Procesar"  onclick="agregar();"/> 
		     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="submit" name="Submit2" value="Cancelar" /></td>
		   </tr>
	  </table>    
</form>








      <?php 
	  include("plus/bottom.lm");
	  ?>
	