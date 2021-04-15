<?php

require 'partials/db.php';
require 'vendor/autoload.php';

$hora = new DateTime("now", new DateTimeZone('America/Bogota'));
    $hora = $hora->format('H:i');
    $hora1 = ( "08:00" );
    $hora2 = ( "20:00" );

    if ($hora<$hora1 || $hora>$hora2) {
        print_r($hora);
    }
    else {
        echo 'entro aqui';
    }


die();


global $conn;
$lista=44;
$userId=46;
    $sql = 'SELECT * FROM contactos WHERE lista=:lista';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':lista', $lista);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    

  

    foreach ($result as $contacto) {
        $numero=$contacto['cod_pais'] . $contacto['celular'].';  ';
        echo $numero;
    }
return 0;








use GuzzleHttp\Client;

$client = new Client([
    'verify' => false,
    // Base URI is used with relative requests
    'base_uri' => 'https://api.cellvoz.co',
    // You can set any number of default request options.
]);
$response = $client->request(
    'POST',
    '/v2/auth/login',
    [
        'headers' => [],
        'json' => ['account' => '00486640445', 'password' => "Lacroso12.."]

    ]
);
$token= ( json_decode($response->getBody()->getContents())) ->token;
$numeros=[];
$numeros[]=50241662183;
$numeros[]=50237230556;
$numeros[]=50212345678;
$numeros[]=50241662183;
$mensajes=[];
foreach ($numeros as $numero) {
 
$response = $client->request(
    'POST',
    '/v2/sms/single',
    [
        'headers' => ['Content-Type' => 'application/json','Authorization' => "Bearer " . $token, 'Api-Key' => 'f0aa1b80d5d1100f8e6688df829ed2d895f9399b'],
        'json' => ['number' => $numero, 'message' => "sebas","type"=>1]

    ]
);

}




print_r( $json_decode($response->getBody()->getContents()));
?>