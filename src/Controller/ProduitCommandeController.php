<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Terrain;
use App\Repository\ArticleRepository;
use App\Repository\AvantProjetRepository;
use App\Repository\ProduitCommandeRepository;
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

class ProduitCommandeController extends AbstractController{
    private ProduitCommandeRepository $ProduitCommandeRepository ;
    private SerializerInterface $serializer;
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;

    public function __construct( ProduitCommandeRepository  $ProduitCommandeRepository ,
                                 SerializerInterface $serializer,
                                 EntityManagerInterface $entityManager,
                                 ValidatorInterface $validator)
    {
        $this->ProduitCommandeRepository = $ProduitCommandeRepository ;
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->validator = $validator;

    }
    /**
     * @Route("/api/getProduitsCommande", name="get_produitsCommande", methods={"GET"})
     */
    public function getProduitCommande (){
        $produitsCommande = $this->ProduitCommandeRepository ->findAll();
        $produitsCommandeJson = $this->serializer->serialize($produitsCommande,'json');
        return new JsonResponse($produitsCommandeJson,Response::HTTP_OK,[],true);
    }



}