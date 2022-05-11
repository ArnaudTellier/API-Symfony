<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\PExe;
use App\Entity\Rubrique;
use App\Entity\Terrain;
use App\Repository\ArticleRepository;
use App\Repository\AvantProjetRepository;
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

class ArticleController extends AbstractController{
    private ArticleRepository $ArticleRepository;
    private EmployeController $employeController;
    private RubriqueController $rubriqueController;
    private SerializerInterface $serializer;
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;

    public function __construct( ArticleRepository $ArticleRepository,
                                 SerializerInterface $serializer,
                                 EmployeController $employeController,
                                 RubriqueController $rubriqueController,
                                 EntityManagerInterface $entityManager,
                                 ValidatorInterface $validator)
    {
        $this->ArticleRepository = $ArticleRepository;
        $this->serializer = $serializer;
        $this->rubriqueController = $rubriqueController;
        $this->employeController = $employeController;
        $this->entityManager = $entityManager;
        $this->validator = $validator;

    }
    /**
     * @Route("/api/articles", name="get_articles", methods={"GET"})
     */
    public function getArticles(){
        $Article = $this->ArticleRepository->findAll();
        $ArticleJson = $this->serializer->serialize($Article,'json');
        return new JsonResponse($ArticleJson,Response::HTTP_OK,[],true);
    }

    /**
     * @Route("/api/article/{id}", name="api_getArticleById", methods={"GET"})
     */
    public function getArticleById($id) {
        $Article = $this->ArticleRepository->find($id);
        $ArticleJson = $this->serializer->serialize($Article,'json');
        return new JsonResponse($ArticleJson,Response::HTTP_OK,[],true);
    }

    /**
     * @Route("/api/article/delete/{id}", name="api_article_delete", methods={"DELETE"})
     */
    public function deleteArticle($id)
    {
        $this->entityManager->remove($this->ArticleRepository->find($id)); // Préparer l'ordre DELETE
        $this->entityManager->flush(); // Envoyer l'ordre DELETE vers la base de données
        return new JsonResponse("Bon",Response::HTTP_OK,[] ,true);
    }

    /**
     * @Route("/api/article/update/{id}", name="api_article_update", methods={"PUT"})
     */
    public function updateArticle(Request $request,$id)
    {
        $articleJson = $request->getContent();
        $article = $this->ArticleRepository->find($id);
        $this->serializer->deserialize($articleJson,Article::class,"json",['object_to_populate'=>$article]);
        $this->entityManager->flush();
        return new JsonResponse("Bon",Response::HTTP_OK,[] ,true);
    }

    /**
     * @Route("/api/article/add", name="api_article_add", methods={"POST"})
     */
    public function addArticle(Request $request)
    {
        try {
            $articleJson = $request->getContent();
            // Désérialiser le json en un objet de la classe ModeleGet
            $article = $this->serializer->deserialize($articleJson,Article::class,"json");
            $responseValidate = $this->validatePexe($article);
            if (!is_null($responseValidate)) {
                return $responseValidate;

            }

            // Enregistrer l'objet $banque dans la base de données
            $this->entityManager->persist($article); // Préparer l'ordre INSERT
            $this->entityManager->flush(); // Envoyer l'ordre INSERT vers la base de données
            // Renvoyer une réponse HTTP
            $articleJson = $this->serializer->serialize($article,'json');
            return new JsonResponse($articleJson,Response::HTTP_CREATED,[],true);
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

    private function validatePexe(Article $article) : ?Response {
        $errors = $this->validator->validate($article);
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
     * @Route("/api/articles/rubrique/{id}", name="getArticlesByRubrique", methods={"GET"})
     */
    public function getArticlesByRubrique($id){
        $articles = $this->ArticleRepository->findBy(["idRubrique" => $id]);
        $articlesJson = $this->serializer->serialize($articles, 'json');
        return new JsonResponse($articlesJson,Response::HTTP_OK,[],true);
    }


}
