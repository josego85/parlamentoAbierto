# Parlamento Abierto



## TODO

* Completar desarrolladores y colaboradores
* Subir automaticamente los datos a google drive


##Documentaci&oacute;n
http://misitio.org//public/doc.html


##Installaci&oacute;n
Requisitos para tener en funcionando en un servidor:
* Php 5

Pasos:
* git clone https://github.com/proyectosbeta/parlamentoAbierto.git
* Cambiar los nombres de las tablas de Google Table Fusion en <b>parlamentoAbierto/public/assets/js/Constantes.js</b> y <b>parlamentoAbierto/public/parseador/constantes.php</b>.
* Crear las 4 tablas (bloques-diputados, diputados, asuntos-diputados, votaciones-diputados) en Google Table FUSION con sus respectivos campos.
* Darle acceso público a las 4 tablas anteriores.

##Cargar asunto y votación.

* Descargar el documento rtf
* Subirlo con la herramienta  http://misitio.org/parseador/subir-diputados.html
* Marcar el resultado del asunto (afirmativo, rechazado, anulado).
* Descargar los csv generados para asunto y votación.
* Subir estos csv a las tablas correspondientes en google drive utilizando como separador el caracter | (pipe).
* Repetir el proceso por cada asunto.

<strong>OBS</strong>: 
<br>
* Subir los csv al google drive antes de procesar el siguiente asunto, pues el asuntoid va incrementando a la ultima obtenida de la tabla en google drive.

