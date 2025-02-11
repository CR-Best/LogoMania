<?php 
$conectar=mysql_connect("localhost","root","")or die("Error en Coneccion");
mysql_select_db("sistema1",$conectar);

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
?>