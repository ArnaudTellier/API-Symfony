<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Rubrique;
use App\Entity\Terrain;
use App\Repository\ArticleRepository;
use App\Repository\AvantProjetRepository;
use App\Repository\RubriqueRepository;
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

header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF8");

class RubriqueController extends AbstractController{
    private RubriqueRepository $rubriqueRespository;
    private SerializerInterface $serializer;
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;

    public function __construct( RubriqueRepository $rubriqueRepository,
                                 SerializerInterface $serializer,
                                 EntityManagerInterface $entityManager,
                                 ValidatorInterface $validator)
    {
        $this->rubriqueRespository = $rubriqueRepository;
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->validator = $validator;

    }
    /**
     * @Route("/api/rubriques", name="get_Rubriques", methods={"GET"})
     */
    public function getRubriques(){
        $rubriques = $this->rubriqueRespository->findAll();
        $rubriquesJson = $this->serializer->serialize($rubriques,'json');
        return new JsonResponse($rubriquesJson,Response::HTTP_OK,[],true);

    }

    /**
     * @Route("/api/rubrique/{id}", name="api_getRubriqueById", methods={"GET"})
     */
    public function getRubriqueById($id)
    {
        $rubrique = $this->rubriqueRespository->find($id);
        $rubriqueJson = $this->serializer->serialize($rubrique,'json');
        return new JsonResponse($rubriqueJson,Response::HTTP_OK,[],true);
    }

    /**
     * @Route("/api/rubrique", name="api_rubrique_add", methods={"POST"})
     */
    public function addRubrique(Request $request)
    {
        try {
            $rubriqueJson = $request->getContent();
            // Désérialiser le json en un objet de la classe ModeleGet
            $rubrique = $this->serializer->deserialize($rubriqueJson,Rubrique::class,"json");
            $responseValidate = $this->validateBanque($rubrique);
            if (!is_null($responseValidate)) {
                return $responseValidate;

            }

            // Enregistrer l'objet $banque dans la base de données
            $this->entityManager->persist($rubrique); // Préparer l'ordre INSERT
            $this->entityManager->flush(); // Envoyer l'ordre INSERT vers la base de données
            // Renvoyer une réponse HTTP
            $rubriqueJson = $this->serializer->serialize($rubrique,'json');
            return new JsonResponse($rubriqueJson,Response::HTTP_CREATED,[],true);
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

    private function validateBanque(Rubrique $rubrique) : ?Response {
        $errors = $this->validator->validate($rubrique);
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
     * @Route("/api/rubrique/{numero}", name="api_rubrique_delete", methods={"DELETE"})
     */
    public function deleteRubrique($numero)
    {
        $this->entityManager->remove($this->rubriqueRespository->find($numero)); // Préparer l'ordre DELETE
        $this->entityManager->flush(); // Envoyer l'ordre DELETE vers la base de données
        return new JsonResponse("Bon",Response::HTTP_OK,[] ,true);
    }

    /**
     * @Route("/api/rubrique/{numero}", name="api_rubrique_update", methods={"PATCH"})
     */
    public function updateRubrique(Request $request,$numero)
    {
        $rubrqiqueJson = $request->getContent();
        $rubrique = $this->rubriqueRespository->find($numero);
        $this->serializer->deserialize($rubrqiqueJson,Rubrique::class,"json",['object_to_populate'=>$rubrique]);
        $this->entityManager->flush();
        return new JsonResponse("Bon",Response::HTTP_OK,[] ,true);
    }





}