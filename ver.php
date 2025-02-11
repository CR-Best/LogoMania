<?php 
include("plus/conexion.lm");


$con=mysql_query("SELECT * FROM ccf",$conectar);

while($si=mysql_fetch_array($con))
{
	echo $si[2];



}