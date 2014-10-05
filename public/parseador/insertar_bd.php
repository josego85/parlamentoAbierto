<?php
	function insertarBd($p_cabecera, $p_votaciones){
        
        //$token = $_SESSION['token'];
	//$ft = new FusionTable($token, API_KEY_GOOGLE_TABLE_FUSION);

                //$v_consulta_diputados = "SELECT * FROM " . NOMBRE_TABLA_DIPUTADOS;
               // $v_array_diputados = json_decode($ft->query($v_consulta_diputados));

		$v_caracter_separador = ",";
		
		// Valores de votacion de los diputados.
		// - 0 = Afirmativo
		// - 1 = Negativo
		// - 2 = Abstencion
		// - 3 = Ausente
       
		
		//$v_asunto = preg_replace(,' ',utf8_encode($p_cabecera['asunto']));  
		//$v_asunto = utf8_decode(preg_replace('/&.*;/', ' ', $p_cabecera['asunto']));
        
                $v_asunto =  utf8_decode($p_cabecera['asunto']);
                $conv = array("?" => "-", "&#147;" => '"', "&#148;" => '"');
                $v_asunto = strtr($v_asunto, $conv);
		$v_ano = $p_cabecera['ano'];
		$v_fecha = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/","$3-$1-$2",$p_cabecera['fecha']);
		$v_hora = $p_cabecera['hora'];
		$v_base = "";
		$v_mayoria = "";
		$v_resultado = $p_cabecera['resultado'];
		$v_presidente = $p_cabecera['presidente'];
		// Se suma la cantidad de si, no, abstencion para los presentes.
		$v_presentes = $p_votaciones['totales']['si'] +  $p_votaciones['totales']['no'] + $p_votaciones['totales']['abstencion'];
		
		$v_ausentes = $p_votaciones['totales']['ausentes'];
		$v_abstenciones = $p_votaciones['totales']['abstencion'];
		$v_afirmativos = $p_votaciones['totales']['si'];
		$v_negativos = $p_votaciones['totales']['no']; 
                  
		$v_votopresidente = $p_cabecera['voto_presidente'];
		$v_titulo = "";
		
		// Los datos de la fila a insertar (asunto-diputado).
		$v_fila_asuntos_votacion_diputado = "(" . "'". $v_asunto . "'". $v_caracter_separador . "'". $v_ano . "'". $v_caracter_separador . "'". $v_fecha . "'".
		    $v_caracter_separador . "'". $v_hora . "'". $v_caracter_separador . "'". $v_base . "'". $v_caracter_separador . "'". $v_mayoria . "'".
		    $v_caracter_separador . "'". $v_resultado . "'". $v_caracter_separador . "'". $v_presidente . "'". $v_caracter_separador . "'". $v_presentes . "'".
		    $v_caracter_separador . "'". $v_ausentes . "'". $v_caracter_separador . "'". $v_abstenciones . "'". $v_caracter_separador . "'". $v_afirmativos . "'".
		    $v_caracter_separador . "'". $v_negativos . "'". $v_caracter_separador . "'". $v_votopresidente . "'".$v_caracter_separador . "'". 
		    $v_titulo . "'". ")";
        
        
                mysql_query("SET AUTOCOMMIT=0");
                mysql_query("START TRANSACTION");
		
		// Insert
		$table = NOMBRE_TABLA_ASUNTOS_DIPUTADOS;
		$v_query = "INSERT INTO $table ( asunto,ano, fecha, hora, base, mayoria, resultado, presidente, 
		    presentes, ausentes, abstenciones, afirmativos, negativos, votopresidente, titulo) VALUES " . $v_fila_asuntos_votacion_diputado;
                $v_result = mysql_query($v_query);

                if(!$v_result){
                    mysql_query("ROLLBACK");
                    return false;
                }
		$v_asuntoId = mysql_insert_id();
        

        
                $v_query = "update $table set permalink = '$v_asuntoId' where asuntoId = '$v_asuntoId'";
                
                $v_result = mysql_query($v_query);

                
                if(!$v_result){
                    mysql_query("ROLLBACK");
                    return false;
                }
                
                $v_query = '';
                
                $table = NOMBRE_TABLA_VOTACIONES_DIPUTADOS;
		foreach($p_votaciones['totales'] as $v_resultado => $v_valor){
		   if(!empty($p_votaciones[$v_resultado])){
                        foreach($p_votaciones[$v_resultado] as $v_nombre_diputados){
                            $v_nombre_diputados = utf8_decode($v_nombre_diputados);
                            $v_query1 = "select diputadoId, bloqueId from diputados where nombre = \"$v_nombre_diputados\"";
                            $result = mysql_query($v_query1);
                            if(mysql_num_rows($result)<=0){
                                mysql_query("ROLLBACK");
                                return false;
                            }
                            $v_obj_diputado = mysql_fetch_array($result);
                            if(!empty($v_obj_diputado)){
                                $v_diputado_id = $v_obj_diputado['diputadoId'];
                                $v_bloque_id = $v_obj_diputado['bloqueId'];
                                switch($v_resultado){
                                    case 'si':
                                         $v_voto = 0;		// El valor 0 indica afirmativo.
                                         break;
                                    case 'no':
                                         $v_voto = 1;		// El valor 1 indica negativo.
                                         break;
                                    case 'abstencion':
                                         $v_voto = 2;		// El valor 2 indica abstencion.
                                         break;
                                    case 'ausentes':
                                         $v_voto = 3;		// El valor 3 indica ausente.
                                         break;
                                }
                                $v_valores_votacion_diputado = "(" . $v_asuntoId . $v_caracter_separador . $v_diputado_id . 
                                $v_caracter_separador . $v_bloque_id . $v_caracter_separador . $v_voto . ")";
					
                                $v_query= "INSERT INTO $table (asuntoId, diputadoId, bloqueId, voto) VALUES " . $v_valores_votacion_diputado . "; ";
                                $v_result = mysql_query($v_query) or die(mysql_error());
                                 if(!$v_result){
                                     mysql_query("ROLLBACK");
                                     return false;
                                 }
                            }
                        }
		    }
		}

		mysql_query("COMMIT");
                return true;
	}

	
	/**
	 * Devuelve un objeto diputado que contiene el diputadoID y id_bloque.
 	 * @param Array $p_array_diputados
 	 * @param string $p_nombre_diputado
 	 * @return Objeto|null
 	 */
	/*function devolverObjDiputado($p_array_diputados, $p_nombre_diputado){
		foreach($p_array_diputados['rows'] as $v_diputado){
			// $v_diputado es un array:
			// - Posicion 0 => diputadoID
			// - Posicion 1 => nombre
			// - Posicion 2 => distrito
			// - Posicion 3 => id_bloque
            //echo mb_detect_encoding($v_diputado[1]);
			//if(mb_detect_encoding($p_nombre_diputado) != 'UTF-8'){
			//$p_nombre_diputado = utf8_encode($p_nombre_diputado);
			//}
			//if(mb_detect_encoding($v_diputado[1]) != 'UTF-8'){
			$v_diputado[1] = utf8_decode($v_diputado[1]);
			
			//print_r($v_diputado[1]);
			//die();
			//}
		//	echo "<br>p_nombre diputado: ". $p_nombre_diputado . "\n";
		//	echo "<br>p_nombre diputado1: ".$v_diputado[1]. "\n\n";;

			if($v_diputado[1] == $p_nombre_diputado){
				$v_obj_diputado = new stdClass();
				$v_obj_diputado->diputadoID = $v_diputado[0];
				$v_obj_diputado->id_bloque = $v_diputado[3];
				return $v_obj_diputado;
			}
		}
		return null; 
	}*/
