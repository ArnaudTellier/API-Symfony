<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Declinaison;
use App\Entity\Terrain;
use App\Repository\ArticleRepository;
use App\Repository\AvantProjetRepository;
use App\Repository\CategorieRepository;
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


class CategorieController extends AbstractController{
    private CategorieRepository $categorieRepository;
    private SerializerInterface $serializer;
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;

    public function __construct( CategorieRepository $categorieRepository,
                                 SerializerInterface $serializer,
                                 EntityManagerInterface $entityManager,
                                 ValidatorInterface $validator)
    {
        $this->categorieRepository = $categorieRepository;
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->validator = $validator;

    }
    /**
     * @Route("/api/categories", name="get_categories", methods={"GET"})
     */
    public function getCategories(){
        $categories = $this->categorieRepository->findAll();
        $categoriesJson = $this->serializer->serialize($categories,'json');
        return new JsonResponse($categoriesJson,Response::HTTP_OK,[],true);
    }

    /**
     * @Route("/api/categorie/{id}", name="api__getCategorieById", methods={"GET"})
     */
    public function getCategorieById($id)
    {
        $categorie = $this->categorieRepository->find($id);
        $categorieJson = $this->serializer->serialize($categorie,'json');
        return new JsonResponse($categorieJson,Response::HTTP_OK,[],true);
    }


    /**
     * @Route("/api/categorie/update", name="api_categorie_add", methods={"POST"})
     */
    public function addCategory(Request $request)
    {
        try {
            $categoryJson = $request->getContent();
            // Désérialiser le json en un objet de la classe ModeleGet
            $category = $this->serializer->deserialize($categoryJson,Categorie::class,"json");
            $responseValidate = $this->validateBanque($category);
            if (!is_null($responseValidate)) {
                return $responseValidate;

            }

            // Enregistrer l'objet $banque dans la base de données
            $this->entityManager->persist($category); // Préparer l'ordre INSERT
            $this->entityManager->flush(); // Envoyer l'ordre INSERT vers la base de données
            // Renvoyer une réponse HTTP
            $categoryJson = $this->serializer->serialize($category,'json');
            return new JsonResponse($categoryJson,Response::HTTP_CREATED,[],true);
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

    private function validateBanque(Categorie $category) : ?Response {
        $errors = $this->validator->validate($category);
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
     * @Route("/api/categorie/{numero}", name="api_categorie_delete", methods={"DELETE"})
     */
    public function deleteCategorie($numero)
    {
        $this->entityManager->remove($this->categorieRepository->find($numero)); // Préparer l'ordre DELETE
        $this->entityManager->flush(); // Envoyer l'ordre DELETE vers la base de données
        return new JsonResponse("Bon",Response::HTTP_OK,[] ,true);
    }

    /**
     * @Route("/api/categorie/{numero}", name="api_categorie_update", methods={"PATCH"})
     */
    public function updateCategorie(Request $request,$numero)
    {
        $categorieJson = $request->getContent();
        $categorie = $this->categorieRepository->find($numero);
        $this->serializer->deserialize($categorieJson,Categorie::class,"json",['object_to_populate'=>$categorie]);
        $this->entityManager->flush();
        return new JsonResponse("Bon",Response::HTTP_OK,[] ,true);
    }







}