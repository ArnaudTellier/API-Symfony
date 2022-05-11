<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Terrain;
use App\Repository\ArticleRepository;
use App\Repository\AvantProjetRepository;
use App\Repository\ProduitRepository;
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


class ProduitController extends AbstractController{
    private ProduitRepository $produitRepository;
    private SerializerInterface $serializer;
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;

    public function __construct( ProduitRepository $produitRepository,
                                 SerializerInterface $serializer,
                                 EntityManagerInterface $entityManager,
                                 ValidatorInterface $validator)
    {
        $this->produitRepository = $produitRepository;
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->validator = $validator;

    }


    /**
     * @Route("/api/produits", name="get_Produits", methods={"GET"})
     */
    public function getProduits(){
        $produits = $this->produitRepository->findAll();
        $produitsJson = $this->serializer->serialize($produits,'json');
        return new JsonResponse($produitsJson,Response::HTTP_OK,[],true);
    }

    /**
     * @Route("/api/produit/{id}", name="api_produit_getProduitById", methods={"GET"})
     */
    public function getProduitById($id)
    {
        $produit = $this->produitRepository->find($id);
        $produitJson = $this->serializer->serialize($produit,'json');
        return new JsonResponse($produitJson,Response::HTTP_OK,[],true);
    }

    /**
     * @Route("/api/produits/categorie/{idCategorie}", name="get_produit_getProduitsByCaterory", methods={"GET"})
     */
    public function getProduitsByCaterory($idCategorie){
        $produits = $this->produitRepository->findBy(["idCategorie" => $idCategorie]);
        $produitsJson = $this->serializer->serialize($produits, 'json');
        return new JsonResponse($produitsJson,Response::HTTP_OK,[],true);
    }

    /**
     * @Route("/api/produit/{numero}", name="api_produit_delete", methods={"DELETE"})
     */
    public function deleteProduit($numero)
    {
        $this->entityManager->remove($this->produitRepository->find($numero)); // Préparer l'ordre DELETE
        $this->entityManager->flush(); // Envoyer l'ordre DELETE vers la base de données
        return new JsonResponse("Bon",Response::HTTP_OK,[] ,true);
    }






}