//©Xara Ltd
if(typeof(loc)=="undefined"||loc==""){var loc="";if(document.body&&document.body.innerHTML){var tt=document.body.innerHTML;var ml=tt.match(/["']([^'"]*)camden.js["']/i);if(ml && ml.length > 1) loc=ml[1];}}

var bd=0
document.write("<style type=\"text/css\">");
document.write("\n<!--\n");
document.write(".camden_menu {z-index:999;border-color:#000000;border-style:solid;border-width:"+bd+"px 0px "+bd+"px 0px;background-color:#0033ff;position:absolute;left:0px;top:0px;visibility:hidden;}");
document.write(".camden_plain, a.camden_plain:link, a.camden_plain:visited{text-align:left;background-color:#0033ff;color:#ffffff;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:10pt;font-family:Arial, Helvetica, sans-serif;}");
document.write("a.camden_plain:hover, a.camden_plain:active{background-color:#ffff99;color:#000000;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:10pt;font-family:Arial, Helvetica, sans-serif;}");
document.write("\n-->\n");
document.write("</style>");

var fc=0x000000;
var bc=0xffff99;
if(typeof(frames)=="undefined"){var frames=0;}

startMainMenu("",0,0,2,0,0)
mainMenuItem("camden_b1",".gif",19,91,"javascript:;","","Facturación",2,2,"camden_plain");
mainMenuItem("camden_b2",".gif",19,91,"javascript:;","","Clientes",2,2,"camden_plain");
mainMenuItem("camden_b3",".gif",19,91,"javascript:;","","Pedidos",2,2,"camden_plain");
mainMenuItem("camden_b4",".gif",19,91,"javascript:;","","Inventario",2,2,"camden_plain");
mainMenuItem("camden_b5",".gif",19,91,"javascript:;","","Reportes",2,2,"camden_plain");
endMainMenu("",0,0);

startSubmenu("camden_b5","camden_menu",91);
submenuItem("Ventas",loc+"../ventas.php","","camden_plain");
submenuItem("Compras",loc+"../compras.php","","camden_plain");
endSubmenu("camden_b5");

startSubmenu("camden_b4","camden_menu",91);
submenuItem("Entrada",loc+"../inventrada.php","","camden_plain");
submenuItem("Salidas",loc+"../invsalida.php","","camden_plain");
submenuItem("Existencias",loc+"../existencias.php","","camden_plain");

endSubmenu("camden_b4");

startSubmenu("camden_b3","camden_menu",91);
submenuItem("Agregar",loc+"../npedido.php","","camden_plain");
submenuItem("Ver",loc+"../vpedido.php","","camden_plain");

endSubmenu("camden_b3");

startSubmenu("camden_b2","camden_menu",91);
submenuItem("Agregar",loc+"../ncliente.php","","camden_plain");
submenuItem("Buscar",loc+"../bcliente.php","","camden_plain");

endSubmenu("camden_b2");

startSubmenu("camden_b1","camden_menu",193);
submenuItem("Comprobante de Crédito Fiscal",loc+"../ccf.php","","camden_plain");
submenuItem("Consumidor Final",loc+"../cf.php","","camden_plain");
submenuItem("Anular documento",loc+"../anular.php","","camden_plain");

endSubmenu("camden_b1");

loc="";
