<?php
/*
* @bauerpy Juan Bauer
 *  */
require "php_ft_lib.php";
require "constantes.php";


$tableid = '';
$username = "";
$password = "";
$key = "";


$token = GoogleClientLogin($username, $password, "fusiontables"); 


$ft = new FusionTable($token, $key); 

//ejemplo insert
$result = $ft->query("INSERT INTO $tableid (Text) VALUES ('kat')"); 


//ejemplo select
$output = $ft->query("SELECT * FROM $tableid"); 


