<?php
/**
*@author bauerpy
 * 
 * IMPORTANTE!!!
 * el usuario y contraseña definidos en esta conexion solo deben tener permiso de realizar select sobre la base de datos
 */

$db_host="localhost";
$db_usuario="votacionespa"; //solo permiso lectura votacionespa vpa_654321
$db_password="vpa_654321";
$db_nombre="votacionespa";
$conexion = mysql_connect($db_host, $db_usuario, $db_password) or die(mysql_error());
$db = mysql_select_db($db_nombre, $conexion) or die(mysql_error()); 