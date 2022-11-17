<?php

	  /*.       ooooooooo.   ooooo 
	 .888.      `888   `Y88. `888' 
	.8"888.      888   .d88'  888  
   .8' `888.     888ooo88P'   888  
  .88ooo8888.    888          888  
 .8'     `888.   888          888  
o88o     o8888o o888o        o888*/ 

use GuzzleHttp\Client;

require  'vendor/autoload.php';

function makeHeaders($token){
	$headers = ['Authorization' => 'Bearer ' .$token, 'Accept' => 'application/json'];
	return $headers;
}

function handleResponse($response){
	if($response->getStatusCode() <300){
	return [
	'code' => $response->getStatusCode(),
	'data' => json_decode($response->getBody()->read(16384),true)['data']
	];
	}
	return [
	'code' => $response->getStatusCode(),
	'msg' => json_decode($response->getBody()->read(16384),true)['msg']
	];
}

/*      dP"Yb   dP""b8 88 88b 88 
88     dP   Yb dP   `" 88 88Yb88 
88  .o Yb   dP Yb  "88 88 88 Y88 
88ood8  YbodP   YboodP 88 88  Y*/ 

function login($username , $password){
	$config = file_get_contents('APIFonction/config.json');
	$config = json_decode($config);
	$client = new \GuzzleHttp\Client([ 'base_uri' => $config->ApiService->url, 'curl' => array(CURLOPT_SSL_VERIFYPEER => false)]);
	try 
	{	
		$response = $client->post('/api/login',  ['json' => ['user__mail' => $username, 'user__password' => $password]]); 
	}
	catch (GuzzleHttp\Exception\ClientException $exeption) // exeption
	{
		$response = $exeption->getResponse(); 
	}
	return [
		'code' => $response->getStatusCode(), 
		'data' => (array) json_decode($response->getBody()->read(16384),TRUE) 
	];
}

function resetPasswordLink($email){
	$config = file_get_contents('APIFonction/config.json');
	$config = json_decode($config);
	$client = new \GuzzleHttp\Client([ 'base_uri' => $config->ApiService->url, 'curl' => array(CURLOPT_SSL_VERIFYPEER => false)]);

	try {
		$response = $client->get('/api/forgot' ,[
			'query' => [ 'user__mail' =>  $email]
		]);
		
	} catch (GuzzleHttp\Exception\ClientException $exeption) {
		$response = $exeption->getResponse();
		
	}
	return json_decode($response->getBody()->read(16384));
}

function updatePassword($secretKey , $password ){
	$config = file_get_contents('APIFonction/config.json');
	$config = json_decode($config);
	$client = new \GuzzleHttp\Client([ 'base_uri' => $config->ApiService->url, 'curl' => array(CURLOPT_SSL_VERIFYPEER => false)]);

	try {
		$response = $client->post('/api/forgot' , [
			'body' => [ 'user__password' =>  $password ,  'confirm__key' => $secretKey]
		]);
		
	} catch (GuzzleHttp\Exception\ClientException $exeption) {
		$response = $exeption->getResponse();
		
	}
	return json_decode($response->getBody()->read(16384));
}

function refresh($refresh_token){
	$config = file_get_contents('APIFonction/config.json');
	$config = json_decode($config);
	$client = new \GuzzleHttp\Client([ 'base_uri' => $config->ApiService->url, 'curl' => array(CURLOPT_SSL_VERIFYPEER => false)]);
		try {
			$response = $client->post('/api/refresh', ['json' => ['refresh_token' => $refresh_token]]);
			
		} catch(GuzzleHttp\Exception\ClientException $exeption) {
			$response = $exeption->getResponse();
		}
	return json_decode($response->getBody()->read(16384),TRUE);
}

/*   88 .dP"Y8 888888 88""Yb 
88   88 `Ybo." 88__   88__dP 
Y8   8P o.`Y8b 88""   88"Yb  
`YbodP' 8bodP' 888888 88  Y*/ 

function getUser($token){
	$config = file_get_contents('APIFonction/config.json');
	$config = json_decode($config);
	$client = new \GuzzleHttp\Client([ 'base_uri' => $config->ApiService->url, 'curl' => array(CURLOPT_SSL_VERIFYPEER => false)]);
	try {
		$response = $client->get('/api/user', ['headers' => makeHeaders($token)]);
		  
	} catch (GuzzleHttp\Exception\ClientException $exeption) {
		$response = $exeption->getResponse(); 
	}
	return handleResponse($response);
}

function getCommercial($token, $com__id){
	$config=file_get_contents('APIFonction/config.json');
	$config=json_decode($config);
	$client=new \GuzzleHttp\Client(['base_uri' => $config->ApiService->url, 'curl' => array(CURLOPT_SSL_VERIFYPEER => false)]);
	try {
		
		$response=$client->get('/api/commercial', ['headers' => makeHeaders($token) , 'query' => ['com__id' => $com__id]]);
	} catch (GuzzleHttp\Exception\ClientException$exeption) {
		$response=$exeption->getResponse();
	 }

	 return [
		'code' => $response->getStatusCode(), 
		'data' => (array) json_decode($response->getBody()->read(16384),TRUE) 
	];
}

/*    d8    db    888888 888888 88""Yb 88 888888 88     
88b  d88   dPYb     88   88__   88__dP 88 88__   88     
88YbdP88  dP__Yb    88   88""   88"Yb  88 88""   88  .o 
88 YY 88 dP""""Yb   88   888888 88  Yb 88 888888 88ood*/

//get materiel peut prendre plusieurs parametres : 
// le token : 
// un tableau de parametre pour la recherche : 
// si des tableau vides sont passés la fonction se contente de remonter 30 matériels de tout les sites appartenant au user par ordre decroissant de creation :
// attention le parametre mat__com__id doit etre indiqué comme ceci : mat__com__id[] car c'est un tableau qui doit etre recupéré de l'autre coté .
// on PEUT préciser un ordre ou plusieur si le code coté API repere qu'une porte DESC ou ASC
// ATTENTION a bien respecter les nom de champs sous peine que rien ne soit renvoyé également merci de TRIM chaque champs de recherche avant d'envoyer )
//exemple de tableau :
// $query = [
// 	"mat__cli__id[]" => 1 , 
// 	"mat__cli__id[]" => 3 , 
// 	"mat__marque[]" => 'hp', 
// 	"mat__marque" => 'DESC' , 
//  "limit => 30
// ];
// exemple de tableau de recherche textuelle : 
$query = [
	"search" => 'petit papa noel', 
  	"limit" => 35 ,
];
//exemples d appel de getMateriel : 
//revoi simplement le parc materiel tout site confondus : 
// list_materiel =  getMateriel($token, []);
//effectue une recherche avec les parametres spécifiés dans le tableau query :  
//  list_materiel =  getMateriel($token, $query);
function getMateriel($token, $query ){
	$config=file_get_contents('APIFonction/config.json');
	$config=json_decode($config);
	$client=new \GuzzleHttp\Client(['base_uri' => $config->ApiService->url, 'curl' => array(CURLOPT_SSL_VERIFYPEER => false)]);
	try
	{
		$response=$client->get('/api/materiel', ['headers' => makeHeaders($token) , 'query' => $query]);
	}
	catch (GuzzleHttp\Exception\ClientException$exeption) 
	{
		$response=$exeption->getResponse();
	}
	//var_dump($response->getBody()->read(16384));
	return handleResponse($response);
}
//   ::::::::::: ::::::::::: ::::::::  :::    ::: :::::::::: ::::::::::: ::::::::    
//      :+:         :+:    :+:    :+: :+:   :+:  :+:            :+:    :+:    :+:    
//     +:+         +:+    +:+        +:+  +:+   +:+            +:+    +:+            
//    +#+         +#+    +#+        +#++:++    +#++:++#       +#+    +#++:++#++      
//   +#+         +#+    +#+        +#+  +#+   +#+            +#+           +#+       
//  #+#         #+#    #+#    #+# #+#   #+#  #+#            #+#    #+#    #+#        
// ###     ########### ########  ###    ### ##########     ###     ########                                    
$body = [
		"tk__motif" => "IN", 
		"tk__titre" => "Test de creation ",
		"tk__lu" => 1	
];
function postTicket($token , $body){
	$config=file_get_contents('APIFonction/config.json');
	$config=json_decode($config);
	$client=new \GuzzleHttp\Client(['base_uri' => $config->ApiService->url, 'curl' => array(CURLOPT_SSL_VERIFYPEER => false)]);
	try
	{
		$response=$client->post('/api/ticket', ['headers' => makeHeaders($token) , 'json' => $body]);
	}
	catch (GuzzleHttp\Exception\ClientException$exeption) 
	{
		$response=$exeption->getResponse();
	}
	return handleResponse($response);
}


$body = [
	"tkl__tk_id" => 1649, 
	"tkl__user_id" => 8 ,
	"tkl__user_id_dest" => 7, 
	"tkl__memo"  => "test de memo"
];
function postTicketLigne($token , $body){
	$config=file_get_contents('APIFonction/config.json');
	$config=json_decode($config);
	$client=new \GuzzleHttp\Client(['base_uri' => $config->ApiService->url, 'curl' => array(CURLOPT_SSL_VERIFYPEER => false)]);
	try
	{
		$response=$client->post('/api/ticketligne', ['headers' => makeHeaders($token) , 'json' => $body]);
	}
	catch (GuzzleHttp\Exception\ClientException$exeption) 
	{
		$response=$exeption->getResponse();
	}
	return handleResponse($response);
}

//passer l id de la ligne liées dans tklc__id : 
$body = [
	"tklc__id" => 1209, 
	"tklc__nom_champ" => "Manger" ,
	"tklc__ordre" => 1, 
	"tklc__memo"  => "des frites avec du paprika ! "
];
function postTicketLigneChamps($token , $body){
	$config=file_get_contents('APIFonction/config.json');
	$config=json_decode($config);
	$client=new \GuzzleHttp\Client(['base_uri' => $config->ApiService->url, 'curl' => array(CURLOPT_SSL_VERIFYPEER => false)]);
	try
	{
		$response=$client->post('/api/ticketchamps', ['headers' => makeHeaders($token) , 'json' => $body]);
	}
	catch (GuzzleHttp\Exception\ClientException$exeption) 
	{
		$response=$exeption->getResponse();
	}
	return handleResponse($response);
}

function getMax($token){
	$config=file_get_contents('APIFonction/config.json');
	$config=json_decode($config);
	$client=new \GuzzleHttp\Client(['base_uri' => $config->ApiService->url, 'curl' => array(CURLOPT_SSL_VERIFYPEER => false)]);
	try
	{
		$response=$client->get('/api/max', ['headers' => makeHeaders($token) ]);
	}
	catch (GuzzleHttp\Exception\ClientException$exeption) 
	{
		$response=$exeption->getResponse();
	}
	return handleResponse($response);
}


function getKeyword($token){
	$config=file_get_contents('APIFonction/config.json');
	$config=json_decode($config);
	$client=new \GuzzleHttp\Client(['base_uri' => $config->ApiService->url, 'curl' => array(CURLOPT_SSL_VERIFYPEER => false)]);
	try
	{
		$response=$client->get('/api/keyword', ['headers' => makeHeaders($token) ]);
	}
	catch (GuzzleHttp\Exception\ClientException$exeption) 
	{
		$response=$exeption->getResponse();
	}
	return handleResponse($response);
}

function getUsersWithToken($token){
	$config=file_get_contents('APIFonction/config.json');
	$config=json_decode($config);
	$client=new \GuzzleHttp\Client(['base_uri' => $config->ApiService->url, 'curl' => array(CURLOPT_SSL_VERIFYPEER => false)]);
	try
	{
		$response=$client->get('/api/usersites', ['headers' => makeHeaders($token) ]);
	}
	catch (GuzzleHttp\Exception\ClientException$exeption) 
	{
		$response=$exeption->getResponse();
	}
	return handleResponse($response);
}


 /*""b8 88     88 888888 88b 88 888888
dP   `" 88     88 88__   88Yb88   88
Yb      88  .o 88 88""   88 Y88   88
 YboodP 88ood8 88 888888 88  Y8   8*/

function updateClient($array_data){
	$config = file_get_contents('APIFonction/config.json');
	$config = json_decode($config);
	$client = new \GuzzleHttp\Client([ 'base_uri' => $config->ApiService->url, 'curl' => array(CURLOPT_SSL_VERIFYPEER => false)]);
	try {
		$response = $client->put('/api/client',  ['json' =>  $array_data]); 
	} catch (GuzzleHttp\Exception\ClientException $exeption) {
		$response = $exeption->getResponse(); 
	}
	return json_decode($response->getBody()->read(16384));
}

// non fonctionel
// function getClient( $token ,$query , $data){
// 	$config = file_get_contents('APIFonction/config.json');
// 	$config = json_decode($config);
// 	$client = new \GuzzleHttp\Client([ 'base_uri' => $config->ApiService->url, 'curl' => array(CURLOPT_SSL_VERIFYPEER => false)]);
// 	try {
// 		$response = $client->put('/api/client',  [  'headers' => makeHeaders($token) ,  'query' => [ $query =>   $data]]); 
// 	} catch (GuzzleHttp\Exception\ClientException $exeption) {
// 		$response = $exeption->getResponse(); 
// 	}
// 	return [
// 		'code' => $response->getStatusCode(), 
// 		'data' => (array) json_decode($response->getBody()->read(16384),TRUE) 
// 	];
// }

?>
