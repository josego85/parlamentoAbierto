<?php
session_start();
	if(!$_SESSION['logged']){
            header('Location: '.'salir.php');
        }
echo "
<a href='salir.php'>Salir</a>
<center>
    <br>
    <br>
    <h1>Subir votos diputados</h1>
    <small>El archivo se va a subir autom&aacute;ticamente a Google Table Fusion</small>
	<form action='parseador.php' method='post' enctype='multipart/form-data'>
	<input type='file' name='votacion' />
	Resultado:
	<select name='resultado'>
	    <option value='AFIRMATIVO'>AFIRMATIVO</option>
	    <option value='NEGATIVO'>NEGATIVO</option>
	    <option value='ANULADA'>ANULADA</option>
	</select>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Presidente:
	<select name='presidente'>
		<option value='' selected='selected'></option>
		
	    <option value='Alsimio Casco'>Alsimio Casco</option>
	    <option value='Amado Florentín'>Amado Florent&iacute;n</option>
	    <option value='Andrés Retamozo'>Andr&eacute;s Retamozo</option>
	    <option value='Ariel Oviedo V.'>Ariel Oviedo V.</option>
	    <option value='Asa González'>Asa Gonz&aacute;lez</option>
	    <option value='Atilio Penayo'>Atilio Penayo</option>
	    <option value='Bernardo Villalba'>Bernardo Villalba</option>
	    <option value='Blanca Vargas de Caballero'>Blanca Vargas de Caballero</option>
	    <option value='Carlos Maggi'>Carlos Maggi</option>
	    <option value='Carlos Núñez S.'>Carlos N&uacute;&ntilde;ez S.</option>
	    
	    <option value='Carlos Portillo'>Carlos Portillo</option>
	    <option value='Celso Kennedy'>Celso Kennedy</option>
	    <option value='Celso Maldonado D.'>Celso Maldonado D.</option>
	    <option value='Celso Troche'>Celso Troche</option>
	    <option value='Clemente Barrios M.'>Clemente Barrios M.</option>
	    <option value='Concepción Quintana'>Concepci&oacute;n Quintana</option>
	    <option value='Cornelius Sawatzky'>Cornelius Sawatzky</option>
	    <option value='Cynthia Tarragó'>Cynthia Tarrag&oacute;</option>
	    <option value='Dany Durand'>Dany Durand</option>
	    <option value='Del Pilar Medina'>Del Pilar Medina</option>
	    
	    <option value='Dionisio Amarilla'>Dionisio Amarilla</option>
	    <option value='E. Antonio Buzarquis'>E. Antonio Buzarquis</option>
	    <option value='Eber Ovelar'>Eber Ovelar</option>
	    <option value='Edgar Acosta'>Edgar Acosta</option>
	    <option value='Edgar Ortíz R.'>Edgar Ort&iacute;z R.</option>
	    <option value='Elio Cabral'>Elio Cabral</option>
	    <option value='Enrique Pereira'>Enrique Pereira</option>
	    <option value='Esmérita Sánchez'>Esm&eacute;rita S&aacute;nchez</option>
	    <option value='Eusebio Alvarenga'>Eusebio Alvarenga</option>
	    <option value='Fabiola Oviedo'>Fabiola Oviedo</option>
	    
	    <option value='Félix Ortellado'>F&eacute;lix Ortellado</option>
	    <option value='Freddy D'Ecclesiss'>Freddy D'Ecclesiss</option>
	    <option value='Gustavo Cardozo'>Gustavo Cardozo</option>
	    <option value='Héctor Lesme'>H&eacute;ctor Lesme</option>
	    <option value='Horacio Carísimo'>Horacio Car&iacute;simo</option>
	    <option value='Hugo Rubín'>Hugo Rub&iacute;n</option>
	    <option value='Hugo Velázquez M.'>Hugo Vel&aacute;zquez M.</option>
	    <option value='Jorge Avalos M.'>Jorge Avalos M.</option>
	    <option value='Jorge Baruja F.'>Jorge Baruja F.</option>
	    <option value='José Adorno'>Jos&eacute; Adorno</option>
	    
	    <option value='José Gregorio Ledesma'>Jos&eacute; Gregorio Ledesma</option>
	    <option value='José María Ibáñez'>Jos&eacute; Mar&iacute;a Ib&aacute;&ntilde;ez</option>
	    <option value='Juan B. Ramírez'>Juan B. Ram&iacute;rez</option>
	    <option value='Juan Félix Bogado'>Juan F&eacute;lix Bogado</option>
	    <option value='Julio Mineur D.'>Julio Mineur D.</option>
	    <option value='Julio Ríos Bogado'>Julio R&iacute;os Bogado</option>
	    <option value='Karina Rodríguez'>Karina Rodr&iacute;guez</option>
	    <option value='Luis Larré'>Luis Larr&eacute;</option>
	    <option value='Marcial Lezcano'>Marcial Lezcano</option>
	    <option value='María Carisimo'>Mar&iacute;a Carisimo</option>
	    
	    <option value='María Cristina Villalba'>Mar&iacute;a Cristina Villalba</option>
	    <option value='María Rocío Casco'>Mar&iacute;a Roc&iacute;o Casco</option>
	    <option value='Mario Cáceres'>Mario C&aacute;ceres</option>
	    <option value='Mario Soto'>Mario Soto</option>
	    <option value='Miguel Del Puerto'>Miguel Del Puerto</option>
	    <option value='Mirta Mendoza'>Mirta Mendoza</option>
	    <option value='Nazario Rojas'>Nazario Rojas</option>
	    <option value='Néstor Ferrer'>N&eacute;stor Ferrer</option>
	    <option value='Olga Ferreira'>Olga Ferreira</option>
	    <option value='Olimpio Rojas'>Olimpio Rojas</option>
	    
	    <option value='Oscar González D.'>Oscar Gonz&aacute;lez D.</option>
	    <option value='Oscar Tuma'>Oscar Tuma</option>
	    <option value='Oscar Venancio Núñez'>Oscar Venancio N&uacute;&ntilde;ez</option>
	    <option value='Pablino Rodríguez'>Pablino Rodr&iacute;guez</option>
	    <option value='Pastor Vera Bejarano'>Pastor Vera Bejarano</option>
	    <option value='Pedro Alliana'>Pedro Alliana</option>
	    <option value='Pedro Britos'>Pedro Britos</option>
	    <option value='Pedro Duré'>Pedro Dur&eacute;</option>
	    <option value='Perla A. de Vázquez'>Perla A. de V&aacute;zquez</option>
	    <option value='Purificación Morel'>Purificaci&oacute;n Morel</option>
	    
	    <option value='Ramón Duarte'>Ram&oacute;n Duarte</option>
	    <option value='Ramón Romero Roa'>Ram&oacute;n Romero Roa</option>
	    <option value='Ricardo González'>Ricardo Gonz&aacute;lez</option>
	    <option value='Salustiano Salinas M.'>Salustiano Salinas M.</option>
	    <option value='Sergio Rojas'>Sergio Rojas</option>
	    <option value='Tadeo Rojas'>Tadeo Rojas</option>
	    <option value='Tomás Rivas'>Tom&aacute;s Rivas</option>
	    <option value='Víctor González S.'>V&iacute;ctor Gonz&aacute;lez S.</option>
	    <option value='Víctor Ríos O.'>V&iacute;ctor R&iacute;os O.</option>
	    <option value='Walter Harms'>Walter Harms</option>
	</select>
	<input type='submit' value='Subir' onclick='this.value='Enviando...'; this.disabled='disabled'; this.form.submit();'/>
	</form>
</center>";