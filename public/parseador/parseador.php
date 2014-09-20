<?php
    require 'limpiarArchivoRTF.php';
    //require 'generarCSV.php';
    require 'insertarGoogleTableFusion.php';

    // Datos del archivo.
    $tmp_name = $_FILES["votacion"]["tmp_name"];
    $name = $_FILES["votacion"]["name"];
    move_uploaded_file($tmp_name, getcwd().'/'.$name);

    $v_objeto = new limpiarArchivoRTF();

    $v_archivo_rtf_limpio = $v_objeto->rtf2text($name);
    //$v_archivo_rtf_limpio = utf8_decode($v_objeto->rtf2text($name));
    //print_r($v_archivo_rtf_limpio);
    //die();
    unlink(getcwd().'/'.$name);
    $primero = preg_split("/[\n]+/",$v_archivo_rtf_limpio); // Romper el string en elementos por salto de lineas.
    //$segundo = array_filter($primero,'trim');				// Eliminar elementos vacios del array.
    $tercero = array_map('trim', $primero); 				// Limpiar los caracteres en blanco de los elementos.

    $cabecera = array();
    $votaciones = array();
    $key = 'otros';
    $cantidad = 0;
    $flag_asunto = false;

    foreach ($tercero as $elemento){
        // Fecha hora.
        if(!isset($cabecera['fecha'])){
            if(preg_match('/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/', substr($elemento,0,10))==1){
                $v_fecha_hora = preg_split("/[\s]+/",$elemento);

                // $v_fecha_hora:
                // $v_fecha_hora[0] -> fecha.
                // $v_fecha_hora[1] -> hora. 

		        // Se obtiene el mes y el dia, porque en Google Table fusion debe ser MM/dd/yyyy
                // y en los documentos estan como dd/MM/yyyy
                $v_dia = substr($v_fecha_hora[0],0,2);
                $v_mes = substr($v_fecha_hora[0],3,2);
                $v_ano = substr($v_fecha_hora[0],6,4);

                $cabecera['fecha'] = $v_mes . "/". $v_dia . "/" . $v_ano;
                $cabecera['hora'] = $v_fecha_hora[1];
                $cabecera['ano'] = $v_ano;  
                continue;
            }
        }      

        // Nombre propuesta.
        if(!isset($cabecera['asunto'])){
            if(strtolower(substr($elemento,0,6)) == 'nombre'){
                $cabecera['asunto'] = '';
                $flag_asunto = true;
                continue;
            }          
        }

        // Concatenar todo el nombre.
        if($flag_asunto){
            $asunto = trim($cabecera['asunto']);
            if(!empty($elemento) || empty($asunto)){
                $cabecera['asunto'] .= ' '.$elemento;
            }else{
                 $flag_asunto = false;
            }
            continue;
        }
        // Resultado.
        $cabecera['resultado']=$_POST['resultado'];
      /*  if(!isset($cabecera['resultado'])){
            if(strpos(strtolower($elemento) ,'aprobado')!== false){
                $cabecera['resultado'] = 'AFIRMATIVO';
            }elseif(strpos(strtolower($elemento) ,'rechazado')!== false){
                 $cabecera['resultado'] = 'NEGATIVO';
            }elseif(strpos(strtolower($elemento) , 'ratificado')!== false){
                 $cabecera['resultado'] = 'ANULADA';
            }
            continue;
        }*/

        if (empty($elemento)) {
            continue;
        }

    // Votos.
        if($cantidad > 0){
            $cantidad--;
            $votaciones[$key][] = $elemento;
        }else{
             switch (strtolower(substr($elemento,0,3))){
                case 'si ':case 'si(':
                    $key = 'si';
                    $cantidad = preg_replace("/[^0-9]/", "", $elemento);
                    break;
                case 'no ':case 'no(':
                    $key = 'no';
                    $cantidad = preg_replace("/[^0-9]/", "", $elemento);
                    break;
                case 'no-':
                    $key = 'ausentes';
                    $cantidad = preg_replace("/[^0-9]/", "", $elemento);
                    break;
                case 'abs':
                    $key = 'abstencion';
                    $cantidad = preg_replace("/[^0-9]/", "", $elemento);
                    break;
                default :
                    $key = 'otros';
                    break;
              }
              $votaciones['totales'][$key]=$cantidad;
         }
   	}

    //print_r($cabecera);
   	//print_r($votaciones);
   	
    // Llama a la funcion generarCSV
    //generarCSV($cabecera, $votaciones);

    // Llama a la funcion insertarGoogleTableFusion
    insertarGoogleTableFusion($cabecera, $votaciones);
    
    //echo "Se ha subido correctamente.";
    //header('Location: '.'subido.html');
