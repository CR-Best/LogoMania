<?php 
include("plus/conexion.lm");
if(isset($_GET["idpedido"]))
{
	$con=mysql_query("SELECT * FROM pedidos WHERE idpedido=\"$_GET[idpedido]\" AND idcliente=\"$_GET[idcliente]\"", $conectar);
	
	if(mysql_num_rows($con)>0)
	{
		$actualizar=mysql_query("UPDATE pedidos SET estadopedido=\"$_GET[ac]\" WHERE idpedido=\"$_GET[idpedido]\" AND idcliente=\"$_GET[idcliente]\"", $conectar);
	
	
	}

}

		  	$fech=mktime(0, 0, 0, date("m")  , date("d")-7, date("Y"));
			$comp=date("Y-m-d",$fech);

$producto="";

$eliviejos="DELETE FROM pedidos WHERE fechaentrega<\"$comp\"";

$ejec=mysql_query($eliviejos,$conectar)or die("No se pudo eliminar");;
include("plus/header.lm");
?>






<?php
include("plus/top.lm");
?>
          
		  
		  Ver: <a href=vpedido.php?cri=1>Pendientes</a> | <a href=vpedido.php?cri=2>En Proceso</a> | 
		  <a href=vpedido.php?cri=3>Finalizados</a> | 
		  <a href=vpedido.php>Todos</a>
		  <table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
              
		<tr>
          <td class="titulo"><img src="img/bcliente.jpg" width="300" height="75" /></td>
          </tr>
		   <?php

	$sql = "SELECT * FROM pedidos "; 
	if(isset($_GET["cri"]))
	{
		$crite=$_GET["cri"];
		
		switch($crite)
		{
		case "1": 	
		$sql.=" WHERE estadopedido=\"$crite\"";
		break;
		
		case "2": 	
		$sql.=" WHERE estadopedido=\"$crite\"";
		break;
		
		case "3": 	
		$sql.=" WHERE estadopedido=\"$crite\"";
		break;
		}
	}
	$sql.="ORDER BY fechaentrega ASC"; 
	$buscar=mysql_query($sql,$conectar)or die("error en consulta");
	$total=mysql_num_rows($buscar);
	echo "<tr><td class=titulo>Registros encontrados: <b>$total</b></td></tr>";
		   ?>
		  <?php
		  
		  
		

		  
		  while($res=mysql_fetch_array($buscar))
		  {
		  	$prod=mysql_query("SELECT * FROM productos WHERE idproducto=\"$res[idproducto]\"",$conectar)or die("Error");
			while($produ=mysql_fetch_array($prod))
				$producto=$produ["nombreproducto"];
				
			$clie=mysql_query("SELECT * FROM clientes WHERE idcliente=\"$res[idcliente]\"",$conectar);
			while($cliente=mysql_fetch_array($clie))
				$nombrecliente=$cliente["nombrecliente"];

				$imagen="pendiente";
				if ($res["estadopedido"]==2)
					$imagen="proceso";
				if ($res["estadopedido"]==3)
					$imagen="terminado";
				
	$fechaen=$res["fechaentrega"];			
list ($dia,$mes,$year)=split('-', $fechaen);
$fechaen=$year."/".$mes."/".$dia;
				
	$fechape=$res["fechapedido"];			
list ($dia,$mes,$year)=split('-', $fechape);
$fechape=$year."/".$mes."/".$dia;


		  echo "<tr>
            <td><img src=img/$imagen.jpg align=right width=100 height=80><b>Fecha de pedido:</b> $fechape - <b>Fecha de Entrega:</b> $fechaen<br>
			<b>Detalle</b>: $res[cantidadproducto] - ($res[idproducto]) $producto<br>
			<b>Precio:</b>$ ".number_format($res["precio"], 2, '.', '')."<br>
			<b>Descripcion:</b> $res[descripcion]<br>
			
			<b>Cliente:</b> $nombrecliente<br><br><b><a href=mpedido.php?idpedido=$res[idpedido]>Modificar este pedido</a><br><br>
			<b>Cambiar estado de pedido:</b> 
			<a href=vpedido.php?idpedido=$res[idpedido]&&idcliente=$res[idcliente]&&ac=1>
			Pendiente</a> &int;
			<a href=vpedido.php?idpedido=$res[idpedido]&&idcliente=$res[idcliente]&&ac=2>
			En Proceso</a> &int;
			<a href=vpedido.php?idpedido=$res[idpedido]&&idcliente=$res[idcliente]&&ac=3>
			Terminado</a> &int;</td></tr>";

		  }
		  ?>
		  
		  
		 <tr>
		   <td align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		   </tr>
      </table>
          
<?php 
	  include("plus/bottom.lm");
	  ?>
	