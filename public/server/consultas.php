<?php
/**
*@author bauerpy
 * 
 */

require 'conexion.php';

$consulta = $_GET['sql'];

$result = mysql_query($consulta);
//echo $consulta;
//print_r(mysql_num_rows ( $result));
$data = array();
while ($fila = mysql_fetch_assoc($result,2)) {
    $data[] = array_map("utf8_encode",$fila);
    //$data[] = $fila;
}
//print_r($data);
$json_data = json_encode($data);
//print_r($json_data);
echo $_GET['callback'] . '(' . "{'rows' : $json_data}" . ')';