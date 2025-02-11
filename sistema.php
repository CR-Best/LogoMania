<?php 
include("plus/conexion.lm");



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

$mensaje="";
if(isset($_GET["msg"]))
{
	$mensajito=$_GET["msg"];
	
	switch($mensajito)
	{
	case "ca": 	
	$mensaje="<marquee behavior=alternate scrollamount=15><span class=mensajes align=center>Cliente Agregado Satisfactoriamente. </span></marquee><br><br>";
	break;
	
	case "cac": 	
	$mensaje="<marquee behavior=alternate scrollamount=15><span class=mensajes align=center>Cliente Actualizado Satisfactoriamente. </span></marquee><br><br>";
	break;
	
	
	case "cb": 	
	$mensaje="<marquee behavior=alternate scrollamount=15><span class=mensajes align=center>Cliente Eliminado Satisfactoriamente. </span></marquee><br><br>";
	break;
	
		
	
	}

	
}
	echo $mensaje;

?>





<table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
        <tr>
          <td width="50%" class="titulo">Bienvenido <i><?php echo $_SESSION["nombre"]; ?></i> </td>
          <td class="titulo">REGISTRO DE PEDIDOS</td>
        </tr>
        <tr>
          <td>
		  
		  <strong>Actividades de Hoy:</strong><br /><br />

		  <?php
		  	$fech=mktime(0, 0, 0, date("m")  , date("d")-5, date("Y"));
			$comp=date("Y-m-d",$fech);
		  	$eli=mysql_query("DELETE FROM actividades WHERE tiempo<=\"$comp\"",$conectar);
		  
		  
		  if(isset($_POST["agenda"]))
		  {
		  		$tiempo="$_POST[year]-$_POST[mes]-$_POST[dia]";
				$conversion=$_POST["horas"];
				
				if($_POST["24h"]==12 && $_POST["horas"]<>12)
					$conversion=$_POST["horas"]+$_POST["24h"];
				
				$hora="$conversion:$_POST[minutos]:00";
				$texto="INSERT INTO actividades VALUES (\"$_SESSION[user]\",\"$tiempo\",\"$hora\",\"$_POST[actividad]\")";
				$grabarhora=mysql_query($texto,$conectar) or die("Error");
		  
		  
		  }
		  
		  
		  
		  
		  $fechahoy=date("Y-m-d");
		  	$acti=mysql_query("SELECT * FROM actividades WHERE idusuario=\"$_SESSION[user]\" AND tiempo=\"$fechahoy\" ORDER BY hora ASC",$conectar);
			
			if(mysql_num_rows($acti)==0)
				echo "No hay actividades registradas para este d&iacute;a";
			else
			{
				while($reci=mysql_fetch_array($acti))
				{
					$horat=$reci["hora"];
					$med="AM";




list ($h,$m,$s)=split(':', $horat);
					
					if($h>12)
					{
						$h=$h-12;
						$med="PM";
					}	
						

					echo "<strong>$h:$m $med:</strong> $reci[actividad]<br><hr>";	
				}
			}
		  
		  
		  
		  
		  
		  ?>
		  <form action="sistema.php" method="post" name="agenda">
		  <table width="95%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2"  class="titulo">Agregar actividad </td>
    </tr>
  <tr>
    <td><strong>Dia</strong></td>
    <td><input name="dia" type="text" id="dia" size="1"   <?php echo "value=".date("d"); ?> />
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
    <td><strong>Hora</strong></td>
    <td><input name="horas" type="text" id="horas" size="1"   <?php 
	
			  	$ho=mktime(date("h")-1, 0, 0, date("m")  , date("d")-5, date("Y"));
			$nh=date("h",$ho);

	
	echo "value=$nh"; ?> /> 
    : 
      <input name="minutos" type="text" id="minutos" size="1" <?php echo "value=".date("i"); ?>> <select name="24h" id="24h">
        <?php
			$meri="";
			if (date("A")=="PM")
				$meri="selected";
		?>
		
		
		<option value="0" selected>AM</option>
        <option value="12" <?php echo $meri;?>>PM</option>
      </select>
      </td>
  </tr>
  <tr>
    <td><strong>Descripcion</strong></td>
    <td><textarea name="actividad" cols="35" rows="4" id="actividad"></textarea></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input name="agenda" type="submit" id="agenda" value="Agregar!" /></td>
    </tr>
</table>

		  
		  
		  
		  
		  
		  </form>
		  
		  
		  </td>
          <td valign="top">
		  
		  <?php
		  $cons=mysql_query("SELECT * FROM pedidos WHERE estadopedido=\"2\"",$conectar);
		  echo "<br>En proceso: <a href=vpedido.php?cri=2><strong>".mysql_num_rows($cons)."</strong></a> <br />";
		  
		  $cons=mysql_query("SELECT * FROM pedidos WHERE estadopedido=\"1\"",$conectar);
		  echo "Pendientes: <a href=vpedido.php?cri=1><strong>".mysql_num_rows($cons)."</strong></a> <br />";
		  
		  
		  	$sql = "SELECT * FROM pedidos ORDER BY fechaentrega ASC LIMIT 0,1"; 
	$buscar=mysql_query($sql,$conectar)or die("error en consulta");
	
			while($fec=mysql_fetch_array($buscar))
			{
				$fechaen=$fec["fechaentrega"];			
				list ($dia,$mes,$year)=split('-', $fechaen);
				$fechaen=$year."/".$mes."/".$dia;

				echo "Pr&oacute;xima fecha de entrega: <a href=vpedido.php><strong>$fechaen</strong></a>";
			
			}

		  ?>          <br />
<br />
<?php
if($_SESSION["nivel"]==1)
{
?>
<table width="100%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
<tr><td class=titulo>Control de Usuarios</td></tr><tr><td><a href="#" onClick="nv('users.php?accion=nu&&id=00')">Crear usuario</a><br />
<br />

<?php
$us=mysql_query("SELECT * FROM users",$conectar);
while($usre=mysql_fetch_array($us))
{

	echo "<a href=# onclick=\"nv('users.php?accion=mod&&id=$usre[idusuario]')\">$usre[nombre]</a><a href=\"#\" onclick=\"nv('users.php?accion=eli&&id=$usre[idusuario]')\"> (Eliminar)</a><br>";


}


?>



</td></tr></table>

<?php
}
?>
        </tr>
</table>
      <?php 
	  include("plus/bottom.lm");
	  ?>
	