<?php
set_include_path(get_include_path() . PATH_SEPARATOR . '/var/www/public/google-api-php-client/src');
require_once 'Google/Client.php';
require_once 'Google/Service/Books.php';

class bd{
    
    function insertar(){
        

        $client = new Google_Client();
        $client->setApplicationName("Client_Library_Examples");
        $apiKey = "<YOUR_API_KEY>";

        $client->setDeveloperKey($apiKey);
        $service = new Google_Service_Fusiontables($client);

    }
    
}



