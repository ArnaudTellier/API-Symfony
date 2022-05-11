<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Terrain;
use App\Repository\ArticleRepository;
use App\Repository\AvantProjetRepository;
use App\Repository\CommandeRepository;
use App\Repository\CommentaireRepository;
use App\Repository\DeclinaisonProduitRepository;
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

class DeclinaisonProduitController extends AbstractController{
    private DeclinaisonProduitRepository $DeclinaisonProduitRepository  ;
    private SerializerInterface $serializer;
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;

    public function __construct( DeclinaisonProduitRepository   $DeclinaisonProduitRepository  ,
                                 SerializerInterface $serializer,
                                 EntityManagerInterface $entityManager,
                                 ValidatorInterface $validator)
    {
        $this->DeclinaisonProduitRepository   = $DeclinaisonProduitRepository  ;
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->validator = $validator;

    }
    /**
     * @Route("/api/getDeclinaisonsProduit", name="get_declinaisonsProduit", methods={"GET"})
     */
    public function getDeclinaisonsProduit (){
        $declinaisonsProd = $this->DeclinaisonProduitRepository  ->findAll();
        $declinaisonsProdJson = $this->serializer->serialize($declinaisonsProd,'json');
        return new JsonResponse($declinaisonsProdJson,Response::HTTP_OK,[],true);
    }



}