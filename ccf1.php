<?php 
include("plus/conexion.lm");


if(isset($_POST["iddocumento"]))
{
		$cant="";
		$des="";
		$pu="";
		$pt="";
		$pedi="";
		$del="";
	$string.="<table width=\"100%\" border=\"1\" cellpadding=\"1\" cellspacing=\"1\" bordercolor=\"#164E7F\">
             <tr class=titulo>
               <td width=\"1%\">CANT.</td>
               <td>Descripcion</td>
               <td width=\"10%\">Precio Un. </td>
               <td width=\"10%\">Ventas Gravadas </td>
             </tr>";
			 
	for($u=1;$u<=$_POST["npedido"];$u++)
	{
		$cant="cant$u";
		$des="des$u";
		$pu="pu$u";
		$pt="pt$u";
		$pedi="pedi$u";
		$string.="<tr><td>$_POST[$cant]</td><td> - $_POST[$des]</td><td>$_POST[$pu]</td><td>$_POST[$pt]</td></tr>";
		
		if($u>1)
			$del.=" OR ";
		
		$del.="idpedido=\"$_POST[$pedi]\"";
		
	}

	$string.="<tr><td colspan=3 align=right><b>Subtotal</b></td><td align=right>$ $_POST[subtotal]</td></tr>
<tr><td colspan=3 align=right><b>I.V.A.</b></td><td align=right>$ $_POST[iva]</td></tr>
<tr><td colspan=3 align=right><b>Total</b></td><td align=right>$ $_POST[total]</td></tr></table>";
	
 $archivo = 'plus/ultfac.txt';
$fp = fopen($archivo, "w");
$write = fputs($fp, $string);
fclose($fp); 

$fp = fopen($archivo, "rb");
$contenido = fread($fp, filesize($archivo));
$contenido = addslashes($contenido);
fclose($fp);
$fecha="$_POST[year]-$_POST[mes]-$_POST[dia]";
$qry = "INSERT INTO ccf VALUE (\"$_POST[iddocumento]\",\"$fecha\",\"$contenido\",\"$_POST[subtotal]\",\"$_POST[idcliente]\")";
mysql_query($qry,$conectar);

$qry = "DELETE FROM pedidos WHERE $del";
mysql_query($qry,$conectar);


header("location:ccf.php");
}



include("plus/header.lm");


?>


<body>

<?php
include("plus/top.lm");


?>



<form action="ccf1.php" method="post" name="ccf" id="ccf">
<table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">

	    <tr>
          <td colspan="2" class="titulo"><img src="img/ccf.jpg" width="300" height="75" /></td>
    </tr>
		   
         <tr>
           <td><strong>No. de Documento: </strong></td>
           <td>
		   
		   <?php 
		   $doc=mysql_query("SELECT * FROM ccf ORDER BY iddocumento ASC LIMIT 0,1",$conectar);
		   $ndoc=1;
		   if(mysql_num_rows($doc)>0)
		   {
		   		$ultnum=mysql_fetch_row($doc);
				$ndoc=$ultnum[0]+1;
		   
		   
		   }
		   
		   
		   ?><input name="iddocumento" type="text" id="iddocumento"  <?php echo "value=\"$ndoc\""; ?>></td>
         </tr>
         <tr> <td width="24%"><b>Nombre del Cliente: </b></td>
		 <?php
		 	$sql=mysql_query("SELECT idcliente, nombrecliente FROM clientes WHERE idcliente=\"$_POST[idcliente]\"", $conectar);
			$cli=mysql_fetch_row($sql);
		 
		 
		 ?>
          <td width="76%"><input name="ncliente" type="text" id="ncliente" size="50" <?php echo "value=\"$cli[1]\""; ?>>          
            <input name="idcliente" type="hidden" id="idcliente"  <?php echo "value=\"$cli[0]\""; ?>>
			
            <strong>Registro</strong>: 
			
					 <?php
		 	$sql1=mysql_query("SELECT * FROM documentos_ccf WHERE idcliente=\"$_POST[idcliente]\"", $conectar);
			$cli1=mysql_fetch_row($sql1);
		 
		 
		 ?>

            <input name="registrocliente" type="text" id="registrocliente" size="15" <?php echo "value=\"$cli1[1]\""; ?>></td>
         </tr>
         <tr>
           <td><strong>Giro</strong>:</td>
           <td><input name="giroclente" type="text" id="giroclente" size="50" <?php echo "value=\"$cli1[2]\""; ?>> 
             Fecha: <select name="dia" id="dia">
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
      </select></td>
         </tr>
         <tr>
           <td colspan="2"><table width="100%" border="1" cellpadding="1" cellspacing="1" bordercolor="#164E7F">
             <tr class=titulo>
               <td width="1%">CANT.</td>
               <td>Descripcion</td>
               <td width="10%">Precio Un. </td>
               <td width="10%">Ventas Gravadas </td>
             </tr>
			 <?php
			 $total=0;
				$anticipos=0;
				$nped=0;
				$r=0;
			 for($u=1;$u<=$_POST["npedido"];$u++)
			 {
				$texto="pedido$u";
				if(isset($_POST[$texto]))
				{
				$bped=mysql_query("SELECT * FROM pedidos WHERE idpedido=\"$_POST[$texto]\"",$conectar)or die("Error");
				$nped++;
				$r++;
				$reped=mysql_fetch_row($bped);
				
				echo "<tr><input name=pedi$r type=hidden value=\"$_POST[$texto]\">";
				
				echo "<td><input name=cant$r type=text size=3 value=\"$reped[8]\"></td>
				
               <td>";
			   $anticipos+=$reped[7];
				$bprod=mysql_query("SELECT * FROM productos WHERE idproducto=\"$reped[4]\"",$conectar)or die("Error");
				
				$reprod=mysql_fetch_row($bprod);
			   $precio=$reped[8]*($reped[6]/1.13);
				$total+=$precio;
			   
			   echo "<textarea name=\"des$r\" cols=\"80\" rows=\"2\">$reprod[1]</textarea></td>
              
			  
			   <td align=right>$ <input name=pu$r type=text size=6 value=\"".number_format(($reped[6]/1.13), 2, '.', '')."\"></td>
               
			   
			   <td align=right>$ <input name=pt$r type=text size=6 value=\"".number_format($precio, 2, '.', '')."\"></td>
			   
			   ";
			 	echo "</tr>";
				 }
			 }
			 
			 echo "<tr><td colspan=3 align=right><b>Subtotal</b></td><td align=right>$ <input name=subtotal type=text size=6 value=\"".number_format($total, 2, '.', '')."\"></td></tr>";
			 echo "<tr><td colspan=3 align=right><b>I.V.A.</b></td><td align=right>$ <input name=iva type=text size=6 value=\"".number_format(($total*0.13), 2, '.', '')."\"></td></tr>";
	echo "<tr><td colspan=3 align=right><b>Total (sin anticipo)</b></td><td align=right>$ <input name=totalsa type=text size=6 value=\"".number_format((($total+($total*0.13))-$anticipos), 2, '.', '')."\"></td></tr>";
			 echo "<tr><td colspan=3 align=right><b>Total</b></td><td align=right>$ <input name=total type=text size=6 value=\"".number_format(($total+($total*0.13)), 2, '.', '')."\"></td></tr>";
			 ?>
               
           </table></td>
           </tr>
         <tr>
           <td>&nbsp;</td>
           <td><input name="npedido" type="hidden" id="npedido"  <?php echo "value=\"$nped\""; ?>>
		   		<input type="submit" name="Submit" value="Generar"></td>
         </tr>
  </table>    
</form>



      <?php 
	  include("plus/bottom.lm");
	  ?>
	