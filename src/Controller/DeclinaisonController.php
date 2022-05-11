<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Avenant;
use App\Entity\Banque;
use App\Entity\Declinaison;
use App\Entity\Terrain;
use App\Repository\ArticleRepository;
use App\Repository\AvantProjetRepository;
use App\Repository\CommandeRepository;
use App\Repository\CommentaireRepository;
use App\Repository\DeclinaisonRepository;
use App\Repository\TvaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DeclinaisonController extends AbstractController{
    private DeclinaisonRepository $DeclinaisonRepository ;
    private SerializerInterface $serializer;
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;

    public function __construct( DeclinaisonRepository  $DeclinaisonRepository ,
                                 SerializerInterface $serializer,
                                 EntityManagerInterface $entityManager,
                                 ValidatorInterface $validator)
    {
        $this->DeclinaisonRepository  = $DeclinaisonRepository ;
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->validator = $validator;

    }
    /**
     * @Route("/api/getDeclinaisons", name="get_declinaisons", methods={"GET"})
     */
    public function getDeclinaisons(){
        $declinaisons = $this->DeclinaisonRepository ->findAll();
        $declinaisonsJson = $this->serializer->serialize($declinaisons,'json');
        return new JsonResponse($declinaisonsJson,Response::HTTP_OK,[],true);
    }

    /**
     * @Route("/api/declinaison/{id}", name="api__getdeclinaisonById", methods={"GET"})
     */
    public function getDeclinaisonById($id)
    {
        $declinaison= $this->DeclinaisonRepository->find($id);
        $declinaisonJson = $this->serializer->serialize($declinaison,'json');
        return new JsonResponse($declinaisonJson,Response::HTTP_OK,[],true);
    }

    /**
     * @Route("/api/declinaison", name="api_declinaison_add", methods={"POST"})
     */
    public function addDeclinaison(Request $request)
    {
        try {
            $declinaisonJson = $request->getContent();
            // Désérialiser le json en un objet de la classe ModeleGet
            $declinaison = $this->serializer->deserialize($declinaisonJson,Declinaison::class,"json");
            $responseValidate = $this->validateBanque($declinaison);
            if (!is_null($responseValidate)) {
                return $responseValidate;

            }

            // Enregistrer l'objet $banque dans la base de données
            $this->entityManager->persist($declinaison); // Préparer l'ordre INSERT
            $this->entityManager->flush(); // Envoyer l'ordre INSERT vers la base de données
            // Renvoyer une réponse HTTP
            $declinaisonJson = $this->serializer->serialize($declinaison,'json');
            return new JsonResponse($declinaisonJson,Response::HTTP_CREATED,[],true);
        } // Intercepter une éventuelle exception
        catch (NotEncodableValueException $exception) {
            $error = [
                "status" => Response::HTTP_BAD_REQUEST,
                "message" => "Le JSON envoyé dans la requête n'est pas valide"
            ];
            // Générer une reponse JSON
            return new JsonResponse(json_encode($error),Response::HTTP_BAD_REQUEST,[],true);
        }
    }

    private function validateBanque(Declinaison $declinaison) : ?Response {
        $errors = $this->validator->validate($declinaison);
        // Tester si il y a des erreurs
        if (count($errors)) {
            // Il y a erreurs
            // Renvoyer les erreurs sous la forme d'une réponse au format JSON
            $errorsJson = $this->serializer->serialize($errors,'json');
            return new JsonResponse($errorsJson,Response::HTTP_BAD_REQUEST,[],true);
        }
        return null;
    }
    /**
     * @Route("/api/declinaison/{numero}", name="api_declinaison_delete", methods={"DELETE"})
     */
    public function deleteDeclinaison($numero)
    {
        $this->entityManager->remove($this->DeclinaisonRepository->find($numero)); // Préparer l'ordre DELETE
        $this->entityManager->flush(); // Envoyer l'ordre DELETE vers la base de données
        return new JsonResponse("Bon",Response::HTTP_OK,[] ,true);
    }

    /**
     * @Route("/api/declinaison/{numero}", name="api_declinaison_update", methods={"PATCH"})
     */
    public function updateDeclinaison(Request $request,$numero)
    {
        $declinaisonJson = $request->getContent();
        $declinaison = $this->DeclinaisonRepository->find($numero);
        $this->serializer->deserialize($declinaisonJson,Declinaison::class,"json",['object_to_populate'=>$declinaison]);
        $this->entityManager->flush();
        return new JsonResponse("Bon",Response::HTTP_OK,[] ,true);
    }




}