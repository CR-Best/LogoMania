<?php 
include("plus/conexion.lm");


include("plus/header.lm");
?>
<?php

include("plus/top.lm");


?>
<table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
              
		   <?php
	$sql = "SELECT * FROM material"; 
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
            <td align=center>$ ".number_format((($row[2]*100)/100), 2, '.', '')."</a></td>
            <td align=center>$row[1] $res[medidamaterial]</td>
			
          </tr>";
		  }
		  echo "</table>";
?>
<?php 
	  include("plus/bottom.lm");
	  ?>
	