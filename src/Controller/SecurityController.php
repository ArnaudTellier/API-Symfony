<?php

namespace App\Controller;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

header("Content-type: application/json; charset=UTF8");
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers');
header('Access-Control-Max-Age: 1728000');
//header('Content-Length: 0');

class SecurityController extends AbstractController{

    /**
     * @Route("/api/login", name="api_login_check", methods={"POST"})
     */
    public function login()
    {
        $content = file_get_contents("php://input");
        // Conversion du JSON -> tableau associatif
        $credentials = json_decode($content,true);
        $employes = $this->employeRepository->findOneBy(array('mail' => $credentials['email']));
        // Test si l'utilisateur existe
        if (!$employes) {
            http_response_code(401);
            $response = [
                "message" => "Bad credentials"
            ];
            echo json_encode($response);
            exit();

        }
        if (!password_verify($credentials['password'], $employes->getMotDePasse())) {
            http_response_code(401);
            $response = [
                "message" => "Bad credentials"
            ];
            echo json_encode($response);
            exit;
        }
        // Création du payload
        $payload = [
            "firstname"=>$employes->getPrenom(),
            "lastname"=>$employes->getNom(),
            "role"=>$employes->getIdRole()->getIdRole(),
            "email"=>$credentials['email']
        ];
        $payloadJson = $this->serializer->serialize($payload,'json');
        $JWT = new JWT();
        $token = $JWT->generate($payload,"test",960);
        $response = [
            "token" => $token
        ];
        return new JsonResponse(json_encode($response),Response::HTTP_OK,[] ,true);
        
    }

    /**
     * @Route("/api/register", name="api_register", methods={"POST"})
     */
    public function register() 
    {
        
    }
}