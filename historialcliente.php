<?php 
include("plus/conexion.lm");

if(!isset($_GET["id"]))
	header("location:bcliente.php");
	
	$sql = "SELECT * FROM clientes WHERE idcliente=\"$_GET[id]\""; 
	
	$buscar=mysql_query($sql,$conectar)or die("Error en consulta");
	if(mysql_num_rows($buscar)==0)
	header("location:bcliente.php");

	$datos=mysql_fetch_row($buscar);

include("plus/header.lm");
?>


<body>


<?php
include("plus/top.lm");

?>





<table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
        <tr>
          <td colspan="2" class=titulo><strong>Historial de <?php echo 
		  $datos[1]; ?></strong><br />
          <br />        </td>
          </tr>
        <tr><td colspan="2" class=titulo>Pedidos Actuales </td>
          </tr>
		<?php
		
		$pedis=mysql_query("SELECT * FROM pedidos WHERE idcliente=\"$datos[0]\"",$conectar);
		if(mysql_num_rows($pedis)>0)
		{
		while($res=mysql_fetch_array($pedis))
		  {
		  	$prod=mysql_query("SELECT * FROM productos WHERE idproducto=\"$res[idproducto]\"",$conectar)or die("Error");
			while($produ=mysql_fetch_array($prod))
				$producto=$produ["nombreproducto"];
				

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


		  echo "<tr><td colspan=\"2\"><img src=img/$imagen.jpg align=right width=100 height=80><b>Fecha de pedido:</b> $fechape - <b>Fecha de Entrega:</b> $fechaen<br>
			<b>Detalle</b>: $res[cantidadproducto] - ($res[idproducto]) $producto<br>
			<b>Precio:</b>$ ".number_format($res["precio"], 2, '.', '')."<br>
			<b>Descripcion:</b> $res[descripcion]<br>
			
			<br><br>
<hr></td></tr>";

		  }

			echo "";
		
		
		}
		else
		{
		echo "<tr>
          <td colspan=2>Actualmente no hay pedidos para este cliente.</td>
        </tr>";
		
		}
		
		
		?>
          
        
        <tr>
          <td colspan="2" class=titulo>Documentos emitidos </td>
          </tr>

		<?php
		$do="ccf";
		if($datos[8]==2)
			$do="cf";
			
		$pedis=mysql_query("SELECT * FROM $do WHERE idcliente=\"$datos[0]\"",$conectar);
		if(mysql_num_rows($pedis)>0)
		{
			$o=0;
			$cade="";
		while($res=mysql_fetch_array($pedis))
		  {	
				$o++;
				if($o>1)
					$cade.="<br><hr>";
					$cade.= "$o- Documento # $res[iddocumento]. Emitido en: ";
				$fechaen=$res["fechadocumento"];			
				list ($dia,$mes,$year)=split('-', $fechaen);
				$fechaen=$year."/".$mes."/".$dia;
								

		  $cade.= "$fechaen ";
		  $str="SELECT * FROM documentos_anulados WHERE iddocumento=\"$res[iddocumento]\"";
		  $docan=mysql_query($str,$conectar) or die("Error");
		  if (mysql_num_rows($docan)>0)
		  	$cade.= "(ANULADO)";


			$cade.="<br><b>Detalle:</b><br>$res[detalledocumento]";
		  }
		  echo "<tr><td colspan=\"2\">$cade
</td></tr>";

			echo "";
		
		
		}
		else
		{
		echo "<tr>
          <td colspan=2>Actualmente no hay pedidos para este cliente.</td>
        </tr>";
		
		}
		
		
		?>





</table>
      <?php 
	  include("plus/bottom.lm");
	  ?>
	