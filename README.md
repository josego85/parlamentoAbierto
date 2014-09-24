# Parlamento Abierto



## TODO

* Completar desarrolladores y colaboradores
* Subir automaticamente los datos a google drive


##Documentaci&oacute;n
http://misitio.org//public/doc.html


##Installaci&oacute;n
Requisitos para tener en funcionando en un servidor:
* Php 5
* mysql

Pasos:
* git clone https://github.com/proyectosbeta/parlamentoAbierto.git
* crear la bd votacionespa e importar el archivo /db/votacionespa.sql
* crear el usuario para consulta publica ejecutando el archivo /db/usuario-publico.sql
* crear el usuario para carga ejecutando el archivo /db/usuario-carga.sql
* Cambiar los nombres de las tablas sql <b>parlamentoAbierto/public/assets/js/Constantes.js</b>, <b>parlamentoAbierto/public/parseador/constantes.php</b> y <b>parlamentoAbierto/public/server/constantes.php</b>

##Cargar asunto y votación.

* Descargar el documento rtf
* Subirlo con la herramienta  http://misitio.org/parseador/ logueandose con el usuario y contraseña de carga del mysql
* Marcar el resultado del asunto (afirmativo, rechazado, anulado).
* Elegir presidente y su voto
* Repetir el proceso por cada asunto.


