<?php
/*
* @bauerpy Juan Bauer
 *  */
require "php_ft_lib.php";
require "constantes.php";


$tableid = '1NI2gWkeW9ZyZiQ2TmenBZr23vcUMlZFzN9KO5Zgf';
$username = "bauerpy@gmail.com";
$password = "chmod 777 www";
$key = "AIzaSyDXCk5wXDCvckFWZb2PLAT6PwxFkoUcmVM";


$token = GoogleClientLogin($username, $password, "fusiontables"); 


$ft = new FusionTable($token, $key); 

//ejemplo insert
$result = $ft->query("INSERT INTO $tableid (asuntoId) VALUES (2)"); 
print_r($result);

//ejemplo select
$output = $ft->query("SELECT * FROM $tableid");
print_r($output);

