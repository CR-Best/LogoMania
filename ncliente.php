<?php 
include("plus/conexion.lm");
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


function vac()
{
	
	if (document.ncliente.nombrecliente.value=="" || document.ncliente.idcliente.value=="")
	{
	
		document.ncliente.registrocliente.focus();
		alert("Ingrese un nombre y codigo para este cliente por favor!");
		return false;

	}

}	


function cfinal()
{
	
	document.ncliente.registrocliente.value="";
	document.ncliente.girocliente.value="";
	document.ncliente.doc1.value="DUI:";
	document.ncliente.doc2.value="NIT:";
		document.ncliente.registrocliente.focus();

}	  	  
</script>


</head>
<body onLoad="ini();">

<?php
include("plus/top.lm");
?>



<table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
      <form action="ncliente1.php" method="post" name="ncliente" onSubmit="return vac();">
	    <tr>
          <td colspan="2" class="titulo"><img src="img/acliente.jpg" width="300" height="75" /></td>
          </tr>
		   
          <td width="24%"><b>Nombre del Cliente: </b></td>
          <td width="76%"><input name="nombrecliente" type="text" id="nombrecliente" size="50" />
            <input name="idcliente" type="text" id="idcliente" size="2" />
          </td>
        </tr>

		 <tr>
          <td width="24%"><b>Direcci&oacute;n: </b></td>
          <td width="76%"><textarea name="dircliente" cols="50" id="dircliente"></textarea></td>
        </tr>
				 <tr>
          <td width="24%"><b>Tel&eacute;fono: </b></td>
          <td width="76%"><input name="telcliente" type="text" id="telcliente" size="15" /></td>
				 </tr>
				 	 <tr>
          <td width="24%"><b>Celular: </b></td>
          <td width="76%"><input name="cellcliente" type="text" id="cellcliente" size="15" /></td>
				 </tr>
				 				 <tr>
          <td width="24%"><b>FAX: </b></td>
          <td width="76%"><input name="faxcliente" type="text" id="faxcliente" size="15" /></td>
				 </tr>
				 
				 <tr>
          <td width="24%"><b>Correo Electronico: </b></td>
          <td width="76%">
            <input name="correo" type="text" id="correo" size="25" />
            </td>
        </tr>
		 <tr>
          <td width="24%"><b>Clase: </b></td>
          <td width="76%"><select name="clasecliente" id="clasecliente">
            <option>A</option>
            <option>B</option>
            <option>C</option>
          </select>
          </td>
		 </tr>
		  <tr>
          <td width="24%"><b>Tipo de consumidor : </b></td>
          <td width="76%">&nbsp;
            <input name="tipodocumento" type="radio" onClick="cfiscal()" value="1" />
            <strong> C.C.F</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="tipodocumento" type="radio" onClick="cfinal()" value="2" checked />
            <strong>C.F.</strong></td>
		  </tr>
		  <tr>
          <td width="24%"><b>
            <label>
            <input name="doc1" type="text" id="doc1" value="DUI:" class="te" readonly/>
            </label>
          </b></td>
          <td width="76%"><input name="registrocliente" type="text" id="registrocliente" size="25" /></td>
        </tr><tr>
          <td width="24%"><label>
            <input name="doc2" type="text" id="doc2" value="NIT:" class="te" readonly/>
          </label></td>
          <td width="76%"><input name="girocliente" type="text" id="girocliente" size="25" /></td>
        </tr>
		 <tr>
		   <td colspan="2" align="center"><input name="enviar" type="submit" id="enviar" value="Procesar" /> 
		     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="submit" name="Submit2" value="Cancelar" /></td>
		   </tr>
      </form>
	  </table>    
		








      <?php 
	  include("plus/bottom.lm");
	  ?>
	