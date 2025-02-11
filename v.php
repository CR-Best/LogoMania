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

?>





<table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
        <tr>
          <td width="50%">
		  
		  <strong>Actividades de Hoy:</strong><br /><br /></td>
          <td valign="top"><br />
<br />
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td valign="top">        
  </tr>
</table>
      <?php 
	  include("plus/bottom.lm");
	  ?>
	