<?php

class parseadorPresencia{
   	/*
	 * Variables de clase.
	 */
	var $separador_busqueda = ' ';

   	/**
   	 * @method crear_tokens
   	 * @param String $p_busqueda
   	 * @return Array $v_array_busqueda
   	 */
    
    //se remplazo por expresiones regulares
   	public function crear_tokens($p_archivo_rtf_limpio){
   		// Abrir documento RTF
   		//$v_archivo_rtf = file_get_contents('00reg01.rtf');
   		
   		$v_longitud_busqueda = strlen($p_archivo_rtf_limpio);
   		if(!$v_longitud_busqueda){
   			echo "No es un archivo RTF.";
   			exit();
   		}
   		$v_token = "";
   		$v_array_busqueda = array();
   	
   		for($i = 0; $i <= $v_longitud_busqueda; $i++){
   			$caracter_actual = substr($p_archivo_rtf_limpio, $i, 1);
   			
   			//echo "caracter: " . $caracter_actual . "<br>";
   	
   			if($caracter_actual != $this->separador_busqueda){
   				// Si contiene comillas y el $caracter_actual no es comilla, entonces sigue acumulando.
   				$v_token .= $caracter_actual;
   				//echo "token: " . $v_token . "<br>";
   			}else if($caracter_actual == $this->separador_busqueda){
   				
   				//echo "El token es: " . $v_token . "<br>";
   				// Borra token.
   				//$v_token = "";
   				
   				switch($v_token) {
	   				case 'Presentes':
	   				case 'Ausentes':
	   					$v_token_presentes = "";
	   					$v_abre_parentesis = false;
	   					$v_bandera_presentes_terminado = false;
	   					
	   					for($i = $i + 1; $i <= $v_longitud_busqueda; $i++){
	   						$caracter_actual = substr($p_archivo_rtf_limpio, $i, 1);
	   						if($caracter_actual == '('){
	   							// Si el $caracter_actual es un parentesis de abertura,
	   							// entonces cambia a true la variable $v_abre_parentesis.
	   							$v_abre_parentesis = true;
	   						}else if($v_abre_parentesis && $caracter_actual != ')'){
	   							 // Si $v_abre_parentesis es true y el $caracter_actual es diferente a un parentesis de clausura,
	   							 // entonces acumula el valor.
	   							$v_token_presentes .= $caracter_actual;
	   							
	   						}elseif($caracter_actual == ')'){
	   							 // Si el $caracter_actual es un parentesis de clausura,
	   							 // entonces la variable $v_abre_parentesis vuelve a false,
	   							 // y se usa una bandera para avisar que termino de acumular el valor
	   							 // de presentes.
	   							 $v_abre_parentesis = false;
	   							 $v_bandera_presentes_terminado = true;
	   						}
	   						if($v_bandera_presentes_terminado){
	   							$v_datos[$v_token] = $v_token_presentes;
	   							//$v_token_presentes = "";
	   							
	   							// Finalizar. 
	   							break;
	   						}
	   					}
	   					break;
	   				default:
	   					// ignore the tag
	   				}
	   				
	   				// Borra token.
	   				$v_token = "";
   			}
   		}
   		return $v_datos;
   	} // Fin del metodo publico crear_tokens.
}

