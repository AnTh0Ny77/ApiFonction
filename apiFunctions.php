<?php
require 'vendor/autoload.php';
require 'functions/user.php';

//Exemples :::

//login
$user  =  (array) login("anthonybs.pro@gmail.com" , "hello1H.test8");

//recupère les donéées de l utilisateur :

// $user =  getUser($user['token']);
// var_dump($user );
// $user =  (array ) $user[0];

//rafraichi la connexion  (à appeler en heut de chaque page): 
// $new_token = refresh($user['refresh_token']);
// $user['token'] = $new_token;

var_dump(getMateriels($user['token']));
//envoi le lien de nouveau mot de passe sur l'email :
// resetPasswordLink('exemple@gmail.com');

//met a jour le mot de passe : 
// updatePassword('clef_secrete15646546!' , 'nouveau_mot_de_passe');

//met un jour un client (sociète):  prend en parametres un tableau  de données avec des noms identique au champs sql et le champs identifier renseigné
// $data = [
//     "cli__id" =>  "1",
//     "cli__nom"=> "testdemiseàjour", 
//     "cli__adr1"=> "test d adresse",
//     "cli__adr2"=> "",
//     "cli__cp"=> "06000",
//     "cli__ville"=> "Nice",
//     "cli__pays"=> "France",
//     "cli__tel"=> "066269898"
// ];
// updateClient(json_encode($data));

//recupère un client ou recherche un client (societe)
// getClient('cli__id','032259');

