<?php 
include("plus/conexion.lm");
include("plus/header.lm");
?>


<script language="javascript">
function elimi(vinculo, cliente)
{
	var preg="Ha escogido eliminar a " + cliente + ". Esta seguro que desea hacerlo?";
	var answer = confirm(preg);
	var conf=cliente+" sera eliminado";
	if (answer){
		alert(conf);
		window.location = vinculo;
	}
	else{
		alert("Accion cancelada!");
	}


}

</script>

<?php
include("plus/top.lm");
?>
          <table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
              
		<tr>
          <td colspan="5" class="titulo"><img src="img/bcliente.jpg" width="300" height="75" /></td>
          </tr>
		   <?php
		   	$criterio=$_POST["busqueda"];
			$separar=explode(" ",$criterio);
			
			$ag="1";
			$msg="";
			for ($a=1; $a<=count($separar); $a++)
			{
					
				$e=$a-1;
				$msg.=" nombrecliente LIKE \"%$separar[$e]%\"";
				
				if (count($separar)>1 && $a!=count($separar))
					$msg.=" AND ";
				
			}
			
	$sql = "SELECT * FROM clientes WHERE $msg"; 
	$buscar=mysql_query($sql,$conectar);
	$total=mysql_num_rows($buscar);
	echo "<tr><td colspan=5 class=titulo>Registros encontrados: <b>$total</b></td></tr>";
		   ?>
          <tr>
            <td class=titulo><b>Nombre del Cliente</b></td>
            <td align="center" class=titulo>Modificar</td>
            <td align="center" class=titulo>Historial</td>
			<td align="center" class=titulo>Agregar Pedido</td>
            <td align="center" class=titulo>Eliminar</td>
          </tr>
		  <?php
		  
		  


		  
		  while($res=mysql_fetch_array($buscar))
		  {
		  echo "<tr>
            <td>$res[1]</td>
            <td align=center><a href=modificarcliente.php?id=$res[0]><img src=img/modificar.png border=0></a></td>
            <td align=center><a href=historialcliente.php?id=$res[0]><img src=img/historial.png border=0></a></td>
			<td align=center><a href=npedido.php?id=$res[0]><img src=img/historial.png border=0></a></td>
			<td align=center>";
			$fla=0;
			$bus="SELECT * FROM pedidos WHERE idcliente=\"$res[0]\"";
			$consulta=mysql_query($bus, $conectar) or die("Error");
			if(mysql_num_rows($consulta)>=1)
				$fla=1;

			$bus="SELECT * FROM ccf WHERE idcliente=\"$res[0]\"";
			$consulta=mysql_query($bus, $conectar) or die("Error");
			if(mysql_num_rows($consulta)>=1)
				$fla=1;

			$bus="SELECT * FROM cf WHERE idcliente=\"$res[0]\"";
			$consulta=mysql_query($bus, $conectar) or die("Error");
			if(mysql_num_rows($consulta)>=1)
				$fla=1;


			if($fla==1)
			{
				echo "<img src=img/eliminar.png border=0 alt=\"No se puede eliminar este cliente, perderia informacion importante de contabilidad al hacerlo.\">";
			}
			else
			{
            echo "<a onclick=\"elimi('eliminarcliente.php?id=$res[0]', '$res[1]')\"><img src=img/eliminar.png border=0></a>";
			}
			
			echo "</td>
          </tr>";
		  }
		  ?>
		  
		  
		 <tr>
		   <td colspan="4" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		   </tr>
      </table>
          
<?php 
	  include("plus/bottom.lm");
	  ?>
	