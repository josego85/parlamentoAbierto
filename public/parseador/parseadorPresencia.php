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
   	
   	// Function that checks whether the data are the on-screen text.
   	// It works in the following way:
   	// an array arrfailAt stores the control words for the current state of the stack, which show that
   	// input data are something else than plain text.
   	// For example, there may be a description of font or color palette etc.
   	function rtf_isPlainText($s) {
   		$arrfailAt = array("*", "fonttbl", "colortbl", "datastore", "themedata");
   		for ($i = 0; $i < count($arrfailAt); $i++)
   			if (!empty($s[$arrfailAt[$i]])) return false;
   			return true;
   	}
   	
   	public function rtf2text($filename) {
   	// Read the data from the input file.
   		$text = file_get_contents($filename);
   		if (!strlen($text))
   			return "";
   	
   			// Create empty stack array.
   		$document = "";
   		$stack = array();
   		$j = -1;
   		// Read the data character-by- character…
   		for ($i = 0, $len = strlen($text); $i < $len; $i++) {
   		$c = $text[$i];
   	
   			// Depending on current character select the further actions.
   			switch ($c) {
   			// the most important key word backslash
   				case "\\":
   				// read next character
   				$nc = $text[$i + 1];
   	
   				// If it is another backslash or nonbreaking space or hyphen,
   				// then the character is plain text and add it to the output stream.
   				if ($nc == '\\' && rtf_isPlainText($stack[$j])) $document .= '\\';
   				elseif ($nc == '~' && rtf_isPlainText($stack[$j])) $document .= ' ';
   				elseif ($nc == '_' && rtf_isPlainText($stack[$j])) $document .= '-';
   				// If it is an asterisk mark, add it to the stack.
   				elseif ($nc == '*') $stack[$j]["*"] = true;
   				// If it is a single quote, read next two characters that are the hexadecimal notation
   				// of a character we should add to the output stream.
   				elseif ($nc == "'") {
   				$hex = substr($text, $i + 2, 2);
   				if ($this->rtf_isPlainText($stack[$j]))
   				$document .= html_entity_decode("&#".hexdec($hex).";");
   				//Shift the pointer.
   				$i += 2;
   				// Since, we’ve found the alphabetic character, the next characters are control word
   				// and, possibly, some digit parameter.
   				} elseif ($nc >= 'a' && $nc <= 'z' || $nc >= 'A' && $nc <= 'Z') {
   				$word = "";
   				$param = null;
   	
   						// Start reading characters after the backslash.
   				for ($k = $i + 1, $m = 0; $k < strlen($text); $k++, $m++) {
   				$nc = $text[$k];
   				// If the current character is a letter and there were no digits before it,
   				// then we’re still reading the control word. If there were digits, we should stop
   				// since we reach the end of the control word.
   				if ($nc >= 'a' && $nc <= 'z' || $nc >= 'A' && $nc <= 'Z') {
   				if (empty($param))
   					$word .= $nc;
   					else
   					break;
   							// If it is a digit, store the parameter.
   							} elseif ($nc >= '0' && $nc <= '9')
   							$param .= $nc;
   							// Since minus sign may occur only before a digit parameter, check whether
   							// $param is empty. Otherwise, we reach the end of the control word.
   							elseif ($nc == '-') {
   		if (empty($param))
   			$param .= $nc;
   		else
   			break;
   							} else
   			break;
   							}
   				// Shift the pointer on the number of read characters.
   				$i += $m - 1;
   	
   				// Start analyzing what we’ve read. We are interested mostly in control words.
   				$toText = "";
   				switch (strtolower($word)) {
   				// If the control word is "u", then its parameter is the decimal notation of the
   				// Unicode character that should be added to the output stream.
   				// We need to check whether the stack contains \ucN control word. If it does,
   					// we should remove the N characters from the output stream.
   					case "u":
   					$toText .= html_entity_decode("&#x".dechex($param).";");
   					$ucDelta = @$stack[$j]["uc"];
   					if ($ucDelta > 0)
   					$i += $ucDelta;
   					break;
   					// Select line feeds, spaces and tabs.
   					case "par": case "page": case "column": case "line": case "lbr":
   					$toText .= "\n";
   					break;
   					case "emspace": case "enspace": case "qmspace":
   					$toText .= " ";
   					break;
   					case "tab": $toText .= "\t"; break;
   					// Add current date and time instead of corresponding labels.
   					case "chdate": $toText .= date("m.d.Y"); break;
   					case "chdpl": $toText .= date("l, j F Y"); break;
   					case "chdpa": $toText .= date("D, j M Y"); break;
   					case "chtime": $toText .= date("H:i:s"); break;
   					// Replace some reserved characters to their html analogs.
   							case "emdash": $toText .= html_entity_decode("&mdash;"); break;
   							case "endash": $toText .= html_entity_decode("&ndash;"); break;
   									case "bullet": $toText .= html_entity_decode("&#149;"); break;
   									case "lquote": $toText .= html_entity_decode("&lsquo;"); break;
   									case "rquote": $toText .= html_entity_decode("&rsquo;"); break;
   									case "ldblquote": $toText .= html_entity_decode("&laquo;"); break;
   									case "rdblquote": $toText .= html_entity_decode("&raquo;"); break;
   									// Add all other to the control words stack. If a control word
   									// does not include parameters, set &param to true.
   									default:
   									$stack[$j][strtolower($word)] = empty($param) ? true : $param;
   									break;
   				}
   				// Add data to the output stream if required.
   				if ($this->rtf_isPlainText($stack[$j]))
   					$document .= $toText;
   				}
   	
   				$i++;
   				break;
   									// If we read the opening brace {, then new subgroup starts and we add
   									// new array stack element and write the data from previous stack element to it.
   									case "{":
   									array_push($stack, $stack[$j++]);
   									break;
   									// If we read the closing brace }, then we reach the end of subgroup and should remove
   									// the last stack element.
   									case "}":
   									array_pop($stack);
   					$j--;
   					break;
   					// Skip “trash”.
   					case '\0': case '\r': case '\f': case '\n': break;
   					// Add other data to the output stream if required.
   					default:
   					if ($this->rtf_isPlainText($stack[$j]))
   					$document .= $c;
   					break;
   				}
   				}
   				// Return result.
   				return $document;
   				}



}

   $v_objeto = new parseadorPresencia;
   
   $v_archivo_rtf_limpio = $v_objeto->rtf2text('00reg01(1).rtf');
   
   
   var_dump($v_archivo_rtf_limpio);
   $v_valor = $v_objeto->crear_tokens($v_archivo_rtf_limpio);
   //var_dump($v_archivo_rtf_limpio);
   //echo "Valor es: " . $v_valor; 
	
  // var_dump($v_valor);
