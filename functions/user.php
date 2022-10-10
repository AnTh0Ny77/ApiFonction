<?php
require  './vendor/autoload.php';

function makeHeaders($token){
    $headers = ['Authorization' => 'Bearer ' .$token, 'Accept' => 'application/json'];
    return $headers;
}

function login($username , $password){
    $config = file_get_contents('config.json');
    $config = json_decode($config);
    $client = new \GuzzleHttp\Client([ 'base_uri' => $config->ApiService->url, 'curl' => array(CURLOPT_SSL_VERIFYPEER => false)]);
  
    try {
        $response = $client->post('/RESTapi/login',  ['json' => ['user__mail' => $username, 'user__password' => $password]]); 
    } catch (GuzzleHttp\Exception\ClientException $exeption) {
        $response = $exeption->getResponse(); 
    }
    return json_decode($response->getBody()->read(1024));
}

function getUser($token){
    $config = file_get_contents('config.json');
    $config = json_decode($config);
    $client = new \GuzzleHttp\Client([ 'base_uri' => $config->ApiService->url, 'curl' => array(CURLOPT_SSL_VERIFYPEER => false)]);
    try {
        $response = $client->get('/RESTapi/user', ['headers' => makeHeaders($token)]);
          
    } catch (GuzzleHttp\Exception\ClientException $exeption) {
        $response = $exeption->getResponse(); 
    }
    return json_decode($response->getBody()->read(1024));
}

function refresh($refresh_token){
    $config = file_get_contents('config.json');
    $config = json_decode($config);
    $client = new \GuzzleHttp\Client([ 'base_uri' => $config->ApiService->url, 'curl' => array(CURLOPT_SSL_VERIFYPEER => false)]);
        try {
            $response = $client->post('/RESTapi/refresh', ['json' => ['refresh_token' => $refresh_token]]);
            
        } catch(GuzzleHttp\Exception\ClientException $exeption) {
            $response = $exeption->getResponse();
        }
    return json_decode($response->getBody()->read(1024));
}

function resetPasswordLink($email){
    $config = file_get_contents('config.json');
    $config = json_decode($config);
    $client = new \GuzzleHttp\Client([ 'base_uri' => $config->ApiService->url, 'curl' => array(CURLOPT_SSL_VERIFYPEER => false)]);

    try {
        $response = $client->get('/RESTapi/forgot' ,[
            'query' => [ 'user__mail' =>  $email]
        ]);
        
    } catch (GuzzleHttp\Exception\ClientException $exeption) {
        $response = $exeption->getResponse();
        
    }
    return json_decode($response->getBody()->read(1024));
}

function updatePassword($secretKey , $password ){
    $config = file_get_contents('config.json');
    $config = json_decode($config);
    $client = new \GuzzleHttp\Client([ 'base_uri' => $config->ApiService->url, 'curl' => array(CURLOPT_SSL_VERIFYPEER => false)]);

    try {
        $response = $client->post('/RESTapi/forgot' , [
            'body' => [ 'user__password' =>  $password ,  'confirm__key' => $secretKey]
        ]);
        
    } catch (GuzzleHttp\Exception\ClientException $exeption) {
        $response = $exeption->getResponse();
        
    }
    return json_decode($response->getBody()->read(1024));
}

