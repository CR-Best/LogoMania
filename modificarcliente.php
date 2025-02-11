<?php 
include("plus/conexion.lm");
$criterio=$_GET["id"];
	$sql = "SELECT * FROM clientes WHERE idcliente=\"$criterio\""; 
	$buscar=mysql_query($sql,$conectar)or die("error en consulta");
	if(mysql_num_rows($buscar)<=0)
		header("location:sistema.php");
		
		
		
	while($res=mysql_fetch_array($buscar))
	{
		$idcliente=$res["idcliente"];
		$nombrecliente=$res["nombrecliente"];
		$dircliente=$res["dircliente"];
		$telcliente=$res["telcliente"];
		$cellcliente=$res["cellcliente"];
		$faxcliente=$res["faxcliente"];
		$emailcliente=$res["emailcliente"];
		$clasecliente=$res["clasecliente"];
		$tipodocumento=$res["tipodocumento"];
	}
	mysql_free_result($buscar);

	$tabla="cf";
	if($tipodocumento==1)
	{
		$tabla="ccf";
	}
	

		$sql = "SELECT * FROM documentos_".$tabla ." WHERE idcliente=\"$idcliente\""; 
		$reg=mysql_query($sql,$conectar)or die("error en consulta de documento");
		while($doc=mysql_fetch_array($reg))
		{
			$doc1=$doc[1];
			$doc2=$doc[2];

		}

include("plus/header.lm");
?>


<script language="javascript">
function ini()
{
document.ncliente.nombrecliente.focus();
}



function cfiscal()
{
	
	document.ncliente.registrocliente.value="";
	document.ncliente.girocliente.value="";
	document.ncliente.doc1.value="Registro:";
	document.ncliente.doc2.value="Giro:";

	document.ncliente.registrocliente.focus();
}	 


function cfinal()
{
	
	document.ncliente.registrocliente.value="";
	document.ncliente.girocliente.value="";
	document.ncliente.doc1.value="DUI:";
	document.ncliente.doc2.value="NIT:";
	document.ncliente.enviar.focus();
}	  	  
	  	  
</script>


</head>
<body onload="ini();"">


<?php
include("plus/top.lm");
?>



<table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
      <form action="modificarcliente1.php" method="post" name="ncliente">
	    <tr>
          <td colspan="2" class="titulo"><img src="img/modificarcliente.jpg" width="300" height="75" /></td>
          </tr>
		   
          <td width="24%"><b>Nombre del Cliente: </b></td>
          <td width="76%"><input name="nombrecliente" type="text" id="nombrecliente" size="50" <?php echo "value=\"$nombrecliente\"";?>/> <label>
            <input name="idcliente" type="text" id="idcliente" size="5" <?php echo "value=$idcliente";?> readonly/>
          </label></td>
        </tr>

		 <tr>
          <td width="24%"><b>Direcci&oacute;n: </b></td>
          <td width="76%"><textarea name="dircliente" cols="50" id="dircliente" ><?php echo "$dircliente";?></textarea></td>
        </tr>
				 <tr>
          <td width="24%"><b>Tel&eacute;fono: </b></td>
          <td width="76%"><input name="telcliente" type="text" id="telcliente" size="15" <?php echo "value=$telcliente";?>></td>
				 </tr>
				 	 <tr>
          <td width="24%"><b>Celular: </b></td>
          <td width="76%"><input name="cellcliente" type="text" id="cellcliente" size="15" <?php echo "value=$cellcliente";?>></td>
				 </tr>
				 				 <tr>
          <td width="24%"><b>FAX: </b></td>
          <td width="76%"><input name="faxcliente" type="text" id="faxcliente" size="15" <?php echo "value=$faxcliente";?>></td>
				 </tr>
			
				 
				 <tr>
          <td width="24%"><b>Correo Electronico: </b></td>
          <td width="76%">
            <input name="correo" type="text" id="correo" size="25"  <?php echo "value=$emailcliente";?>>
            </td>
        </tr>
		 
		 <tr>
          <td width="24%"><b>Clase: </b></td>
          <td width="76%"><select name="clasecliente" id="clasecliente">
		  
            <option <?php if ($clasecliente=="A") echo "selected";?>>A</option>
            <option <?php if ($clasecliente=="B") echo "selected";?>>B</option>
            <option <?php if ($clasecliente=="C") echo "selected";?>>C</option>
          </select>
          </td>
		 </tr>
		  <tr>
          <td width="24%"><b>Tipo de consumidor : </b></td>
          <td width="76%">&nbsp;
            <input name="tipodocumento" type="radio" onclick="cfiscal()" value="1" <?php if ($tipodocumento==1) echo "checked";?>>
            <strong> C.C.F</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="tipodocumento" type="radio" onclick="cfinal()" value="2"  <?php if ($tipodocumento==2) echo "checked";?>>
            <strong>C.F.</strong></td>
		  </tr>
		  <tr>
          <td width="24%"><b>
            <label>
            <input name="doc1" type="text" id="doc1" value="<?php if ($tipodocumento==1) echo "Registro:"; else echo "DUI:"; ?>" class="te" readonly/>
            </label>
          </b></td>
          <td width="76%"><input name="registrocliente" type="text" id="registrocliente" size="25"  <?php echo "value=\"$doc1\""; ?> ></td>
        </tr><tr>
          <td width="24%"><label>
            <input name="doc2" type="text" id="doc2" value="<?php if ($tipodocumento==1) echo "Giro:"; else echo "NIT:"; ?>" class="te" readonly/>
          </label></td>
          <td width="76%"><input name="girocliente" type="text" id="girocliente" size="25"  <?php echo "value=\"$doc2\""; ?> ></td>
        </tr>
		 <tr>
		   <td colspan="2" align="center"><input name="enviar" type="submit" id="enviar" value="Actualizar" /> 
		     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="submit" name="Submit2" value="Cancelar" /></td>
		   </tr>
      </form>
	  </table>    
		








      <?php 
	  include("plus/bottom.lm");
	  ?>
	