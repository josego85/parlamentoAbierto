<?php

	/**
 	 * @param Array $p_cabecera
 	 * @param Array $p_votaciones
 	 * @return void
 	 */
	function generarCSV($p_cabecera, $p_votaciones){
		header("Content-Type: text/html; charset=utf-8");
		
		// API Key de Google Table Fusion.
		$v_api_key_google_table_fusion = 'AIzaSyDICo1qGOtGnd0DD3QEY_rQ2_xcFGLNYto';
		
		// Nombre de tablas de Google Table Fusion.
		$v_nombre_tabla_diputados = '1i_gDiq1mcYIGoVrA6Kst3fEGvMN6RH2z366C-eW0';
		$v_nombre_bloque_diputados = '1v7GscZrDxlscNy9FbC_dAK7Hl_EKYGL89k9-o8l7';
		
		// Se obtiene el json de la Tabla diputados.
		$v_json_diputados = "https://www.googleapis.com/fusiontables/v1/query?sql=SELECT%20*%20FROM%20" . $v_nombre_tabla_diputados ."&key=" . $v_api_key_google_table_fusion;
		
		// Se obtiene el json de la Tabla bloque diputados.
		$v_json_bloque_diputados = "https://www.googleapis.com/fusiontables/v1/query?sql=SELECT%20*%20FROM%20" . $v_nombre_bloque_diputados ."&key=" . $v_api_key_google_table_fusion;
		
		// Convierte el json de diputados a un array de diputados.
		$v_array_diputados = json_decode(file_get_contents($v_json_diputados), true);
		
		
		// Convierte el json de bloque diputados a un array de bloque diputados.
		$v_array_bloque_diputados = json_decode(file_get_contents($v_json_bloque_diputados, true));
		
		
		//print_r($p_votaciones);

		
		// Generar votaciones diputados en un archivo CSV.
		
		// Valores de votacion de los diputados.
		// - 0 = Afirmativo
		// - 1 = Negativo
		// - 2 = Abstención
		// - 3 = Ausente
		
		$v_caracter_separador = ",";
		$v_archivo_votaciones_csv = "votaciones-diputados.csv";
		$v_cabecera_archivo_votaciones_csv = "asuntoId" . $v_caracter_separador . "diputadoId" . 
		    $v_caracter_separador . "bloqueId" . $v_caracter_separador ."voto \n";
		$v_asuntoId = "1";
		
		if(!$handle = fopen($v_archivo_votaciones_csv, "w")){
			echo "No puede abrir el archivo: " . $v_archivo_votaciones_csv;
			exit;
		}
		if(fwrite($handle, utf8_decode($v_cabecera_archivo_votaciones_csv)) === FALSE){
			echo "No puede escribir en el archivo: " . $v_archivo_votaciones_csv;
			exit;
		}
		
		foreach($p_votaciones['totales'] as $v_resultado => $v_valor){
			switch($v_resultado){
				case 'si':
				case 'ausentes':
					foreach($p_votaciones[$v_resultado] as $v_nombre_diputados){
						$v_obj_diputado = devolverObjDiputado($v_array_diputados, $v_nombre_diputados);
						
						if(!empty($v_obj_diputado)){
							$v_diputado_id = $v_obj_diputado->diputadoID;
							$v_bloque_id = $v_obj_diputado->id_bloque;
							
							switch($v_resultado){
								case 'si':
									$v_voto = 0;		// El valor 0 indica afirmativo.
									break;
								case 'ausentes':
									$v_voto = 3;		// El valor 3 indica ausente.
									break;
							}

							$v_fila_votacion_diputado = $v_asuntoId . $v_caracter_separador . $v_diputado_id . 
							    $v_caracter_separador . $v_bloque_id . $v_caracter_separador . $v_voto . "\n";

							if(fwrite($handle, utf8_decode($v_fila_votacion_diputado)) === FALSE){
								echo "No puede escribir en el archivo: " . $v_archivo_votaciones_csv;
								exit;
							}
						}
					}
					break;
			}
		}
		fclose($handle);
		
		
		
		// Generar asuntos diputados en un archivo CSV.
		$v_caracter_separador = ",";
		$v_archivo_asuntos_votaciones_csv = "asuntos-diputados.csv";
		$v_cabecera_archivo_asuntos_votaciones_csv = "asuntoId" . $v_caracter_separador. "sesion" .
		    $v_caracter_separador . "asunto" . $v_caracter_separador . "ano" . $v_caracter_separador .
		    "fecha" . $v_caracter_separador . "hora" . $v_caracter_separador . "base" . $v_caracter_separador .
		    "mayoria" . $v_caracter_separador . "resultado" . $v_caracter_separador . "presidente" .
		    $v_caracter_separador . "presentes" . $v_caracter_separador . "ausentes" . $v_caracter_separador .
		    "abstenciones" . $v_caracter_separador . "afirmativos" . $v_caracter_separador . "negativos" .
		    $v_caracter_separador . "votopresidente" . $v_caracter_separador . "titulo \n";
		$v_asuntoId = "1";
		
		if(!$handle = fopen($v_archivo_asuntos_votaciones_csv, "w")){
			echo "No puede abrir el archivo: " . $v_archivo_asuntos_votaciones_csv;
			exit;
		}
		if(fwrite($handle, utf8_decode($v_cabecera_archivo_asuntos_votaciones_csv)) === FALSE){
			echo "No puede escribir en el archivo: " . $v_archivo_asuntos_votaciones_csv;
			exit;
		}
		
		//print_r($p_cabecera);
		//print_r($p_votaciones);
		
		//echo "Asunto: " . $p_cabecera['asunto'] . "\n";
		
		$v_sesion = "";
		$v_asunto = $p_cabecera['asunto'];
		$v_asunto = "";
		$v_ano = $p_cabecera['ano'];
		$v_fecha= $p_cabecera['fecha'];
		$v_hora = $p_cabecera['hora'];
		$v_base = "";
		$v_mayoria = "";
		$v_resultado = $p_cabecera['resultado'];
		$v_presidente = "";
		
		// Se suma la cantidad de si, no, abstencion para los presentes.
		$v_presentes = $p_votaciones['totales']['si'] +  $p_votaciones['totales']['no'] + $p_votaciones['totales']['abstencion'];
		
		$v_ausentes = $p_votaciones['totales']['ausentes'];
		$v_abstenciones = $p_votaciones['totales']['abstencion'];
		$v_afirmativos = $p_votaciones['totales']['si'];
		$v_negativos = $p_votaciones['totales']['no'];
		$v_votopresidente = "";
		$v_titulo = "";
		
		// Los datos de la fila a insertar (asunto-diputado).
		$v_fila_asuntos_votacion_diputado = $v_asuntoId . $v_caracter_separador . $v_sesion .
		$v_caracter_separador . $v_asunto . $v_caracter_separador . $v_ano . $v_caracter_separador . $v_fecha .
		    $v_caracter_separador . $v_hora . $v_caracter_separador . $v_base . $v_caracter_separador . $v_mayoria .
		    $v_caracter_separador . $v_resultado . $v_caracter_separador . $v_presidente . $v_caracter_separador . $v_presentes .
		    $v_caracter_separador . $v_ausentes .$v_caracter_separador . $v_abstenciones . $v_caracter_separador . $v_afirmativos .
		    $v_caracter_separador . $v_negativos . $v_caracter_separador . $v_votopresidente .$v_caracter_separador . 
		    $v_titulo . "\n";
		
		if(fwrite($handle, utf8_decode($v_fila_asuntos_votacion_diputado)) === FALSE){
			echo "No puede escribir en el archivo: " . $v_archivo_asuntos_votaciones_csv;
			exit;
		}
		fclose($handle);
	}

	
	/**
	 * Devuelve un objeto diputado que contiene el diputadoID y id_bloque.
 	 * @param Array $p_array_diputados
 	 * @param string $p_nombre_diputado
 	 * @return Objeto|null
 	 */
	function devolverObjDiputado($p_array_diputados, $p_nombre_diputado){
		foreach($p_array_diputados['rows'] as $v_diputado){
			// $v_diputado es un array:
			// - Posicion 0 => diputadoID
			// - Posicion 1 => nombre
			// - Posicion 2 => distrito
			// - Posicion 3 => id_bloque
			if($v_diputado[1] == $p_nombre_diputado){
				
				$v_obj_diputado = new stdClass();
				$v_obj_diputado->diputadoID = $v_diputado[0];
				$v_obj_diputado->id_bloque = $v_diputado[3];
				return $v_obj_diputado;
			}
		}
		return null; 
	}