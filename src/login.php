<?php

use App\JWT;


/*header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers');
header('Access-Control-Max-Age: 1728000');
//header('Content-Length: 0');
header("Content-type: application/json; charset=UTF-8");*/

require "../config/secret.php";

//1 Verifier que la méthode HTTP est POST
//2 Recupérer le body de la requête HTTP (le username et le password)
//3 Rechercher le user dans la table
    // 3.1 Si le user n'existe pas : renvoyer une réponse JSON 401:Bad credentials
    // 3.2 Si le mot de passe  ne correspond pas : une réponse JSON 401:Bad credentials
//4 Générer le payload avec les données du user : username, firstname et lastname
//5 Génerer le token avec la classe JWT
//6 renvoyer une réponse JSON 200:token

// Gestion du CORS : Cross-Origin resource sharing
// Traitement de la requête Preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST,GET,PUT,DELETE,OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    die();
}   

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
       
// 1.
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(405);
    $reponse = [
        "message" => "Méthode HTTP non autorisée"
    ];
    echo json_encode($reponse);
    exit;
}

//2.
// Récupération du body au format JSON de la requête HTTP
$body = file_get_contents("php://input");
// Conversion JSON -> tableau associatif
$credentials = json_decode($body,true);

//3.
// Connexion PDO
$connexion = new PDO('mysql:host=localhost;dbname=afterwork', "root", "");
$requete = $connexion->prepare("SELECT * FROM users WHERE username = :username");
$requete->bindValue(":username",htmlentities($credentials["username"]));
$requete->execute();
$utilisateur = $requete->fetch(PDO::FETCH_ASSOC);

// 3.1 Vérifie si l'utilisateur n'existe pas // Bad credentials pour des raisons de sécurité
if (!$utilisateur) {
    http_response_code(401);
    $reponse = [
        "message" => "Bad credentials"
    ];
    $message = json_encode($reponse);
    echo $message;
    exit;
}


// 3.2 Test du mot de passe
if ( !password_verify($credentials["password"],$utilisateur["password"])) {
    http_response_code(401);
    $reponse = [
        "message" => "Bad credentials"
    ];
    echo json_encode($reponse);
    exit;
}

//4 Générer le payload avec les données du user : username, firstname et lastname
$payload = [
    "username" => $utilisateur["username"],
    "firstname" => $utilisateur["firstname"],
    "lastname" => $utilisateur["lastname"],
];

//5 Génerer le token avec la classe JWT
$jwt = new JWT();
$token = $jwt->generate($payload,SECRET_HMAC,900);

//6 renvoyer une réponse JSON 200:token
http_response_code(200);
$reponse = [
    "token" => $token
];
echo json_encode($reponse);

