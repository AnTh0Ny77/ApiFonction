<?php
require  './vendor/autoload.php';

function makeHeaders($token){
    $headers = ['Authorization' => 'Bearer ' .$token, 'Accept' => 'application/json'];
    return $headers;
}


function updateClient($array_data){
    $config = file_get_contents('config.json');
    $config = json_decode($config);
    $client = new \GuzzleHttp\Client([ 'base_uri' => $config->ApiService->url, 'curl' => array(CURLOPT_SSL_VERIFYPEER => false)]);
    try {
        $response = $client->put('/RESTapi/client',  ['json' =>  $array_data]); 
    } catch (GuzzleHttp\Exception\ClientException $exeption) {
        $response = $exeption->getResponse(); 
    }
    return json_decode($response->getBody()->read(1024));
}

function getClient($query , $data){
    $config = file_get_contents('config.json');
    $config = json_decode($config);
    $client = new \GuzzleHttp\Client([ 'base_uri' => $config->ApiService->url, 'curl' => array(CURLOPT_SSL_VERIFYPEER => false)]);
    try {
        $response = $client->put('/RESTapi/client',  [  'query' => [ $query =>   $data]]); 
    } catch (GuzzleHttp\Exception\ClientException $exeption) {
        $response = $exeption->getResponse(); 
    }
    return json_decode($response->getBody()->read(1024));
}

