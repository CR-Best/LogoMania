<?php 
include("plus/conexion.lm");
if(isset($_POST["flag"]))
{
	$fecha="$_POST[apedido]-$_POST[mpedido]-$_POST[dpedido]";
	$insertar=mysql_query("INSERT INTO documentos_anulados VALUES (\"$_POST[iddocumento]\",\"$_POST[tipodocumento]\",\"$fecha\",\"$_POST[motivo]\")",$conectar) or die("Nop");
	header("location:anular.php");




}
if(isset($_POST["numdoc"]))
{
			$te="";
		  $do="ccf";
		  if($_POST["tipodocumento"]==1)
		  	{
			$te= "Comprobante de Credito Fiscal #";
			}
		else
		{
				  $do="cf";

		  	$te= "Consumidor Final #";
		 } 



		  $concli=mysql_query("SELECT * FROM $do WHERE iddocumento=\"$_POST[numdoc]\"",$conectar)or die("Error 1");
		  $datosfactura=mysql_fetch_row($concli);
			if(mysql_num_rows($concli)==0)
				header("location:anular.php");


	$fechape=$datosfactura[1];			
list ($dia,$mes,$year)=split('-', $fechape);
$fechape=$year."/".$mes."/".$dia;

		
}

include("plus/header.lm");
?>


<script language="javascript">
function nv(pagina)
{
	var int_windowLeft = (screen.width - 600) / 2;
  var int_windowTop = (screen.height - 400) / 2;

	var conca=pagina;
window.open(conca,'usuarios', 'left=' + int_windowLeft +',top=' + int_windowTop +', width=600, height=400,toolbar=0,resizable=0, scrollbars=1');
}

</script>
<body>


<?php
include("plus/top.lm");


if (!isset($_POST["numdoc"]))
{
?>




<form action="anular.php" method="post" name="anular">
<table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
        <tr>
          <td colspan="2" class="titulo">
		  
		  <strong>Anular documento </strong></td>
          </tr>
        <tr>
          <td width="50%">Ingrese el numero de documento: </td>
          <td valign="top"><input name="numdoc" type="text" id="numdoc"></td>        
  </tr>
        <tr>
          <td>Seleccione el tipo de documento: </td>
          <td valign="top"><label>
            <input name="tipodocumento" type="radio" value="1" checked>
            CCF 
            <input name="tipodocumento" type="radio" value="2">
            CF</label></td>
        </tr>
        <tr>
          <td colspan="2" align="center"><input type="submit" name="Submit" value="Enviar"></td>
          </tr>
        <tr>
          <td colspan="2" class="titulo">Documentos Anulados </td>
        </tr>
        <tr>
          <td colspan="2" class="titulo">CCF</td>
        </tr>
        <tr>
          <td colspan="2">
		  <?php 
		  $concli=mysql_query("SELECT * FROM documentos_anulados WHERE tipodocumento=\"1\" ORDER BY iddocumento ASC",$conectar)or die("Error 1");
		  	while($resu=mysql_fetch_array($concli))
			{
				$bus=mysql_query("SELECT * FROM ccf WHERE iddocumento=\"$resu[iddocumento]\"",$conectar);
				$o=0;
				while($res=mysql_fetch_array($bus))
				{
					$o++;
					echo "$o- Documento # $res[iddocumento]. A nombre de: ";
					
					$buscli=mysql_query("SELECT * FROM clientes WHERE idcliente=\"$res[idcliente]\"",$conectar)or die("Error 2");
				  $datoscliente=mysql_fetch_row($buscli);
			  
			  	$fechape=$resu["fechaanulada"];			
list ($dia,$mes,$year)=split('-', $fechape);
$fechape=$year."/".$mes."/".$dia;

			  
			  
				  echo $datoscliente[1]. "<br>Fecha de anulacion: $fechape.<br>
				  Motivo: $resu[motivo]<br><hr>";
				}
			}
		  ?>
</td>
        </tr>
        <tr>
          <td colspan="2" class="titulo">CF</td>
        </tr>
		        <tr>
          <td colspan="2">
		  <?php 
		  $concli=mysql_query("SELECT * FROM documentos_anulados WHERE tipodocumento=\"2\" ORDER BY iddocumento ASC",$conectar)or die("Error 1");
		  	while($resu=mysql_fetch_array($concli))
			{
				$bus=mysql_query("SELECT * FROM cf WHERE iddocumento=\"$resu[iddocumento]\"",$conectar);
				$o=0;
				while($res=mysql_fetch_array($bus))
				{
					$o++;
					echo "$o- Documento # $res[iddocumento]. A nombre de: ";
					
					$buscli=mysql_query("SELECT * FROM clientes WHERE idcliente=\"$res[idcliente]\"",$conectar)or die("Error 2");
				  $datoscliente=mysql_fetch_row($buscli);
			  
			  	$fechape=$resu["fechaanulada"];			
list ($dia,$mes,$year)=split('-', $fechape);
$fechape=$year."/".$mes."/".$dia;

			  
			  
				  echo $datoscliente[1]. "<br>Fecha de anulacion: $fechape.<br>
				  Motivo: $resu[motivo]<br><hr>";
				}
			}
		  ?>
</td>
        </tr>

</table>
</form>
      <?php 
	  }
	  
	  else
	  {
	  
	  ?>
	  
	  <form action="anular.php" method="post" name="anular">
<table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
        <tr>
          <td colspan="2" class="titulo">
		  
		  <strong>Anular documento </strong></td>
          </tr>
        <tr>
          <td width="50%"> <?php echo $te;
		  ?>: </td>
          <td valign="top"><?php echo "<input type=text name=iddocumento value=\"".$_POST["numdoc"]."\">(Emitida el: $fechape)"; ?>
          <input name="tipodocumento" type="hidden" id="tipodocumento" <?php echo "value=$_POST[tipodocumento]"; ?>></td>
        </tr>
       
        <tr>
          <td>Emitido a nombre de : </td>
          <td valign="top"><?php
		  
		  
		  $buscli=mysql_query("SELECT * FROM clientes WHERE idcliente=\"$datosfactura[4]\"",$conectar)or die("Error 2");
		  $datoscliente=mysql_fetch_row($buscli);
		  
		  echo $datoscliente[1];
		  
		  ?></td>
        </tr> <tr>
          <td>Fecha de anulacion: </td>
          <td valign="top"><select name="dpedido" id="dpedido">
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
      </select></td>
        </tr>
        <tr>
          <td>Motivo:</td>
          <td valign="top"><textarea name="motivo" cols="50" rows="5" id="motivo"></textarea></td>
        </tr>
        <tr>
          <td colspan="2">Detalles:<br><?php 
		  echo $datosfactura[2];
		  
		  ?></td>
        </tr>
        <tr>
          <td colspan="2" align="center"><input type="hidden" name="flag"><input type="submit" name="Submit" value="Anular">      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="button" name="Submit2" onClick="javascript:window.location.replace('sistema.php');" value="Cancelar" /></td>
          </tr>
</table>
	  </form>
	  
	  
	  <?php
	  }
	  include("plus/bottom.lm");
	  ?>
	