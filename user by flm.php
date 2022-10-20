<?php

use GuzzleHttp\Client;

require  'vendor/autoload.php';

function makeHeaders($token){
	$headers = ['Authorization' => 'Bearer ' .$token, 'Accept' => 'application/json'];
	return $headers;
}

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
		'data' => (array) json_decode($response->getBody()->read(1024),TRUE) 
	];
}

function getUser($token){
	$config = file_get_contents('APIFonction/config.json');
	$config = json_decode($config);
	$client = new \GuzzleHttp\Client([ 'base_uri' => $config->ApiService->url, 'curl' => array(CURLOPT_SSL_VERIFYPEER => false)]);
	try {
		$response = $client->get('/api/user', ['headers' => makeHeaders($token)]);
		  
	} catch (GuzzleHttp\Exception\ClientException $exeption) {
		$response = $exeption->getResponse(); 
	}
	return [
		'code' => $response->getStatusCode(), 
		'data' => (array) json_decode($response->getBody()->read(1024),TRUE) 
	];
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
	return json_decode($response->getBody()->read(1024));
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
		'data' => (array) json_decode($response->getBody()->read(1024),TRUE) 
	];
}



//get materiels peut prendre plusieurs parametres : 
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
		// $query = [
		// 	"search" => 'petit papa noel', 
		//   "limit => 35 ,
		// 	"mat__marque" => 'DESC'
		// ];
		//exemples d appel de getMateriel : 
			//revoi simplement le parc materiel tout site confondus : 
				// list_materiel =  getMateriel($token, []);
			//effectue une recherche avec les parametres spécifiés dans le tableau query :  
				//  list_materiel =  getMateriel($token, $query);
function getMateriel($token, $query ){
	$config=file_get_contents('APIFonction/config.json');
	$config=json_decode($config);
	$client=new \GuzzleHttp\Client(['base_uri' => $config->ApiService->url, 'curl' => array(CURLOPT_SSL_VERIFYPEER => false)]);
	try {
		
		$response=$client->get('/api/materiel', ['headers' => makeHeaders($token) , 'query' => $query]);
	} catch (GuzzleHttp\Exception\ClientException$exeption) {
		$response=$exeption->getResponse();
	 }

	 return [
		'code' => $response->getStatusCode(), 
		'data' => (array) json_decode($response->getBody()->read(1024),TRUE) 
	];
}


?>
