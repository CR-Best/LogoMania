<?php 
include("plus/conexion.lm");
include("plus/header.lm");
?>

<script language="javascript">
function ini()
{
document.bcliente.busqueda.focus();
}
</script>

</head>
<body onload="ini();"">






<?php
include("plus/top.lm");
?>
          <form action="bcliente1.php" method="post" name="bcliente"><table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
              
		<tr>
          <td colspan="2" class="titulo"><img src="img/bcliente.jpg" width="300" height="75" /></td>
          </tr>
		   
          <td width="24%"><b>Nombre del Cliente: </b></td>
          <td width="76%"><input name="busqueda" type="text" size="50" /></td>
        </tr>
		 <tr>
		   <td colspan="2" align="center"><input type="submit" name="Submit" value="Buscar" /> 
		     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="submit" name="Submit2" value="Cancelar" /></td>
		   </tr>
		   
      </table></form>
<?php 
	  include("plus/bottom.lm");
	  ?>
	