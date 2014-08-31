# Parlamento Abierto



## TODO

* Completar desarrolladores y colaboradores
* Subir automaticamente los datos a google drive

##Installacion
Requisitos para tener en funcionando en un servidor:
- Php 5

Pasos:
1.- git clone https://github.com/proyectosbeta/parlamentoAbierto.git
2.- Cambiar los nombres de las tablas de Google Table Fusion en <b>parlamentoAbierto/public/assets/js/Constantes.js</b> y <b>parlamentoAbierto/public/parseador/constantes.php</b>.
3.- Crear las 4 tablas (bloques-diputados, diputados, asuntos-diputados, votaciones-diputados) en Google Table FUSION con sus respectivos campos.
4.- Darle acceso público a las 4 tablas anteriores.

##Cargar asunto y votación.

* Descargar el documento rtf
* Subirlo con la herramienta  http://misitio.org/parseador/subir-diputados.html
* Marcar el resultado del asunto (afirmativo, rechazado, anulado).
* Descargar los csv generados para asunto y votación.
* Subir estos csv a las tablas correspondientes en google drive utilizando como separador el caracter | (pipe).
* Repetir el proceso por cada asunto.

Obs. importante subir los csv al google drive antes de procesar el siguiente asunto, pues el asuntoid va incrementando a la ultima obtenida de la tabla en google drive.

