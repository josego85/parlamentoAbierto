<?php
/*
* @bauerpy Juan Bauer
 *  */
require "php_ft_lib.php";
require "constantes.php";


$tableid = NOMBRE_TABLA_VOTACIONES_DIPUTADOS;
$username = USERNAME_GOOGLE;
$password = PASSWORD_USERNAME_GOOGLE;
$key = API_KEY_GOOGLE_TABLE_FUSION;

$token = GoogleClientLogin($username, $password, "fusiontables"); 

$ft = new FusionTable($token, $key); 

// Ejemplo insert.
$result = $ft->query("INSERT INTO $tableid (asuntoId, diputadoId, bloqueId, voto) VALUES (2, 8, 1, 3)"); 


// Ejemplo select.
//$output = $ft->query("SELECT * FROM $tableid"); 