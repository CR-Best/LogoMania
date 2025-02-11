<?php
session_start();
session_destroy(); 




?>



<script language="javascript" type="text/javascript">
function MostrarFecha()
   {
	   var nombres_dias = new Array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado")
	   var nombres_meses = new Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre")
	
	   var fecha_actual = new Date()
	
	   dia_mes = fecha_actual.getDate()	
	   dia_semana = fecha_actual.getDay()	
	   mes = fecha_actual.getMonth() + 1
	   anio = fecha_actual.getFullYear()
	
	   document.write(nombres_dias[dia_semana] + ", " + dia_mes + " de " + nombres_meses[mes - 1] + " de " + anio)
   }



function tiempin()
{
timer = setTimeout("redi()", 5000);;


}
function redi()
{
	
	self.close();


}

</script>








<link href="plus/estilo.css" rel="stylesheet" type="text/css" />

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Saliendo de ...::: LoGoMaNiA :::...</title>
</head>

<body onload="tiempin();">
<table width="1%" border="0" cellspacing="0" cellpadding="0" align="center" >
  <tr>
    <td><table width="1%%" border="5" cellpadding="5" cellspacing="5" bordercolor="#164E7F">
  <tr>
    <td><img src="top.jpg" /></td>
  </tr>
  <tr>
    <td align="right" bgcolor="#F27427">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" bgcolor="#FFFFFF">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td align=left>
	<span class="fecha">
	<script language="JavaScript" type="text/javascript">
	MostrarFecha();
	</script> </span><br>
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="135" height="50">
  <param name="movie" value="img/reloj.swf?id=<?php echo rand(1111,9999); ?>" />
  <param name="quality" value="high" />
  <embed src="img/reloj.swf?id=<?php echo rand(1111,9999); ?>" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="135" height="50"></embed>
</object></td>

<td align=right valign=top>&nbsp;
</td></tr></table>

<table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
        <tr>
          <td width="50" class="titulo">HA SALIDO DEL SISTEMA!!!! </td>
          </tr>
        <tr>
          <td>Esta ventana se cerrara en 5 segundos. </td>
        </tr>
</table>

</td>
  </tr>
  <tr>
    <td align="center" bgcolor="#FFFFFF"><img src="img/derechos.jpg" width="400" height="120" /></td>
  </tr>
</table>
</td>
  </tr>
</table>
</body>
</html>

</body>
</html>
