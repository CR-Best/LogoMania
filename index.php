<?php 
$conectar=mysql_connect("localhost","root","")or die("Error en Coneccion");
$base=mysql_select_db("sistema",$conectar);
if(!$base)
{
$crear=mysql_query("CREATE DATABASE sistema",$conectar)or die ("Error");
mysql_select_db("sistema",$conectar);

$texto="CREATE TABLE actividades (idusuario varchar(25) NOT NULL, tiempo date NOT NULL, hora time NOT NULL, actividad varchar(255) NOT NULL) ENGINE = myisam";
$tablas=mysql_query($texto,$conectar)or die ("Error1");


$texto="CREATE TABLE ccf (iddocumento int(6) NOT NULL, fechadocumento date NOT NULL, detalledocumento blob NOT NULL, subtotaldocumento float NOT NULL, idcliente varchar(6) NOT NULL) ENGINE=MyISAM";
$tablas=mysql_query($texto,$conectar)or die ("Error2");

$texto="CREATE TABLE cf (
  iddocumento int(6) NOT NULL,
  fechadocumento date NOT NULL,
  detalledocumento blob NOT NULL,
  subtotaldocumento float NOT NULL,
  idcliente varchar(6) NOT NULL
) ENGINE=MyISAM";
$tablas=mysql_query($texto,$conectar)or die ("Error3");

$texto="CREATE TABLE clientes (
  idcliente text NOT NULL,
  nombrecliente text NOT NULL,
  dircliente text NOT NULL,
  telcliente text NOT NULL,
  cellcliente text NOT NULL,
  faxcliente text NOT NULL,
  emailcliente text NOT NULL,
  clasecliente varchar(1) NOT NULL,
  tipodocumento int(1) NOT NULL
) ENGINE=MyISAM ";
$tablas=mysql_query($texto,$conectar)or die ("Error4");

$texto="CREATE TABLE documentos_anulados (
  iddocumento varchar(6) NOT NULL,
  tipodocumento varchar(1) NOT NULL,
  fechaanulada date NOT NULL,
  motivo varchar(255) NOT NULL
) ENGINE=MyISAM ";
$tablas=mysql_query($texto,$conectar)or die ("Error5");

$texto="
CREATE TABLE documentos_ccf (
  idcliente varchar(6) NOT NULL,
  registrocliente varchar(10) NOT NULL,
  girocliente varchar(50) NOT NULL
) ENGINE=MyISAM";
$tablas=mysql_query($texto,$conectar)or die ("Error6");

$texto="CREATE TABLE documentos_cf (
  idcliente varchar(6) NOT NULL,
  duicliente varchar(10) NOT NULL,
  nitcliente varchar(17) NOT NULL
) ENGINE=MyISAM";
$tablas=mysql_query($texto,$conectar)or die ("Error7");

$texto="CREATE TABLE entradas_inventario (
  fechaentrada date NOT NULL,
  ndocumento varchar(8) NOT NULL,
  idmaterial varchar(6) NOT NULL,
  cantidad_material float NOT NULL,
  costomaterial float NOT NULL
) ENGINE=MyISAM";
$tablas=mysql_query($texto,$conectar)or die ("Error8");

$texto="CREATE TABLE factura (
  iddocumento int(6) NOT NULL,
  fechadocumento date NOT NULL,
  detalledocumento mediumblob NOT NULL,
  subtotaldocumento float NOT NULL,
  idcliente varchar(6) NOT NULL
) ENGINE=MyISAM";
$tablas=mysql_query($texto,$conectar)or die ("Error9");

$texto="CREATE TABLE inventario (
  idmaterial varchar(6) NOT NULL,
  cantidad_material float NOT NULL,
  costomaterial float NOT NULL
) ENGINE=MyISAM";
$tablas=mysql_query($texto,$conectar)or die ("Error10");

$texto="CREATE TABLE material (
  idmaterial varchar(6) NOT NULL,
  nombrematerial varchar(50) NOT NULL,
  medidamaterial varchar(20) NOT NULL
) ENGINE=MyISAM";
$tablas=mysql_query($texto,$conectar)or die ("Error11");

$texto="CREATE TABLE pedidos (
  idpedido varchar(3) NOT NULL,
  idcliente varchar(6) NOT NULL,
  fechapedido date NOT NULL,
  fechaentrega date NOT NULL,
  idproducto varchar(6) NOT NULL,
  descripcion varchar(255) NOT NULL,
  precio float NOT NULL,
  anticipo float NOT NULL,
  cantidadproducto int(4) NOT NULL,
  estadopedido int(1) NOT NULL,
  idusuario varchar(25) NOT NULL
) ENGINE=MyISAM";
$tablas=mysql_query($texto,$conectar)or die ("Error12");

$texto="CREATE TABLE productos (
  idproducto varchar(6) NOT NULL,
  nombreproducto varchar(100) NOT NULL,
  preciocostoproducto float NOT NULL,
  precioventaproducto float NOT NULL
) ENGINE=MyISAM";
$tablas=mysql_query($texto,$conectar)or die ("Error13");

$texto="CREATE TABLE salidas_inventario (
  fechasalida date NOT NULL,
  idmaterial varchar(6) NOT NULL,
  cantidad_material float NOT NULL,
  costomaterial float NOT NULL
) ENGINE=MyISAM";
$tablas=mysql_query($texto,$conectar)or die ("Error14");

$texto="CREATE TABLE users (
  idusuario varchar(16) NOT NULL,
  nombre varchar(50) NOT NULL,
  password varchar(100) NOT NULL,
  nivel char(1) NOT NULL
) ENGINE=MyISAM";
$tablas=mysql_query($texto,$conectar)or die ("Error15");


$agregar=mysql_query("INSERT INTO users VALUES (\"carlos\",\"Carlos Alberto Rosales\",\"admin\",\"1\")",$conectar);





header("location:index.php");



}


include("plus/conexion.lm");
$flag=0;
if(isset($_POST["user"]))
{
	$usuarios=mysql_query("SELECT * FROM users", $conectar);
	if(mysql_num_rows($usuarios)==0)
	{
		if($_POST["user"]=="administrador")
		{
			session_start();

			$_SESSION["user"] = $_POST["user"];
			$_SESSION["pass"] = "rosales";
			$_SESSION["nivel"] = 1;

			$flag=1;
		}else
		{
			header("location:index.php?er=1");
		}
	}
	else
	{
		$usuarios=mysql_query("SELECT * FROM users WHERE idusuario=\"$_POST[user]\" AND password=\"$_POST[pass]\"", $conectar);
		if(mysql_num_rows($usuarios)==0)
		{
			header("location:index.php?er=1");
		}
		else
		{
			$info = mysql_fetch_row($usuarios);
			session_start();

			$_SESSION["user"] = $_POST["user"];
			$_SESSION["pass"] = $_POST["pass"];
			$_SESSION["nombre"] = $info[1];
			$_SESSION["nivel"] = $info[3];

					$flag=1;

		}
	}





}

?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>...::: Bienvenido a LOGOSYS :::...</title>

<link href="plus/estilo.css" rel="stylesheet" type="text/css" />


<script language="javascript">
function ini()
{
document.ingreso.user.focus();
}


function venta()
{

var int_windowLeft = (screen.width - 825) / 2;
  var int_windowTop = (screen.height - 600) / 2;

window.open('sistema.php','principal',
'left=' + int_windowLeft +',top=' + int_windowTop +',fullscreen=1,toolbar=0,resizable=0, scrollbars=1');

}

</script>



</head>

<body <?php 


if($flag==1)
{
echo "onLoad=\"venta();\"";


}
else
{
echo "onLoad=\"ini();\"";

}

?>
>
<table width="1%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><img src="top.jpg" align="left"/><br /></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF"><form action="index.php" method="post" name="ingreso">
<br />
<br /><?php 
if(isset($_GET["er"]))
	echo "<span class=mensajes>Usuario &oacute; Contrase&ntilde;a incorrecta.<br>Por favor, vuelva a intentarlo...</span>";


?>
<table width="50%" border="3" align="center" cellpadding="4" cellspacing="4" bordercolor="#164E7F">
        <tr>
          <td colspan="2" class="titulo">Ingreso al Sistema: </td>
          </tr>
        <tr>
          <td width="50%" bgcolor="#FFFFFF">
		  
		  <strong>Nombre de Usuario </strong></td>
          <td width="1%" bgcolor="#FFFFFF"><label>
            <input name="user" type="text" id="user" size="25" />
          </label></td>
        </tr>
        <tr>
          <td bgcolor="#FFFFFF"><strong>Contrase&ntilde;a</strong></td>
          <td bgcolor="#FFFFFF"><label>
            <input name="pass" type="password" id="pass" size="25" />
          </label></td>
        </tr>
        <tr>
          <td colspan="2" align="center" bgcolor="#FFFFFF"><input type="submit" name="Submit" value="Ingresar" /></td>
          </tr>
      </table>
<br />
    <br />
    </form>
</td>
  </tr>
    <tr>
    <td align="center" bgcolor="#FFFFFF"><img src="img/derechos.jpg" /></td>
  </tr>
</table>








</body>
</html>