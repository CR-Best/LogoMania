<?php 
include("plus/conexion.lm");
$reg=0;

if(isset($_POST["idclientes"]))
{
	$reg=1;
}


include("plus/header.lm");


if($reg==1)
{

	$conpe=mysql_query("SELECT * FROM pedidos WHERE idcliente=\"$_POST[idclientes]\"",$conectar);
	$t2pedi2=mysql_num_rows($conpe);

}
?>

<body>

<?php
include("plus/top.lm");


if($reg==1)
{
?>

<form action="cf1.php" method="POST" name="cf">
<table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">

	    <tr>
          <td colspan="2" class="titulo"><img src="img/cf.jpg" width="300" height="75" /></td>
    </tr>
		   
         <tr> <td width="24%"><b>Nombre del Cliente: </b></td>
		 <?php
		 	$sql=mysql_query("SELECT idcliente, nombrecliente FROM clientes WHERE idcliente=\"$_POST[idclientes]\"", $conectar);
			$cli=mysql_fetch_row($sql);
		 
		 
		 ?>
          <td width="76%"><input name="ncliente" type="text" id="ncliente" size="50" <?php echo "value=\"$cli[1]\""; ?>>          
            <input name="idcliente" type="hidden" id="idcliente"  <?php echo "value=\"$cli[0]\""; ?>>
            <input name="npedido" type="hidden" id="npedido" <?php echo "value=\"$t2pedi2\""; ?>></td>
         </tr>
         <tr>
           <td>Pedidos:</td>
           <td>
		   
		   <?php
		   		$buspe=mysql_query("SELECT * FROM pedidos WHERE idcliente=\"$cli[0]\"",$conectar);
				$i=0;
				while($busped=mysql_fetch_array($buspe))
				{
					$prod=mysql_query("SELECT * FROM productos WHERE idproducto=\"$busped[idproducto]\"",$conectar)or die("Error");
					while($produ=mysql_fetch_array($prod))
					$producto=$produ["nombreproducto"];

					$i++;
					echo "<input type=\"checkbox\" name=\"pedido$i\" value=\"$busped[idpedido]\">
					$busped[cantidadproducto] - ($busped[idproducto]) $producto<br>";
				}
		   
		   
		   ?>
		   
           </td>
         </tr>
         <tr>
           <td colspan="2"><input type="submit" name="Submit" value="Enviar" /></td>
    </tr>
  </table>    
</form>



<?php
}

if($reg==0)
{
?>


<form action="cf.php" method="post" name="cf">
<table width="90%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">

	    <tr>
          <td colspan="2" class="titulo"><img src="img/cf.jpg" width="300" height="75" /></td>
    </tr>
		   
         <tr> <td width="24%"><b>Nombre del Cliente: </b></td>
          <td width="76%"><select name="idclientes" id="idclientes">
<?php
				$idcliente="";
			if(isset($_GET["id"]))
				$idcliente=$_GET["id"];
				
			$sql=mysql_query("SELECT idcliente, nombrecliente FROM clientes WHERE tipodocumento=\"2\" ORDER BY nombrecliente ASC", $conectar);
			while($nombreclientes=mysql_fetch_array($sql))
			{
				$conpe=mysql_query("SELECT * FROM pedidos WHERE idcliente=\"$nombreclientes[0]\"",$conectar);
				
				if(mysql_num_rows($conpe)>0)
				{
				echo "<option value=$nombreclientes[0] ";
				if ($nombreclientes[0]==$idcliente)
					echo "selected";
					
				echo ">$nombreclientes[1]</option>";
				}
			}
			
			?>            
			
			
			
            </select><input name="envio" type="submit" value="Enviar" /></td>
        </tr>
  </table>    
</form>


<?php
}

?>



      <?php 
	  include("plus/bottom.lm");
	  ?>
	