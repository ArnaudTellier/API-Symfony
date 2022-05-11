<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Terrain;
use App\Repository\ArticleRepository;
use App\Repository\AvantProjetRepository;
use App\Repository\StatutCommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StatutCommande extends AbstractController{
    private StatutCommandeRepository $statutCommandeRepository;
    private SerializerInterface $serializer;
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;

    public function __construct( StatutCommandeRepository $statutCommandeRepository,
                                 SerializerInterface $serializer,
                                 EntityManagerInterface $entityManager,
                                 ValidatorInterface $validator)
    {
        $this->statutCommandeRepository = $statutCommandeRepository;
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->validator = $validator;

    }
    /**
     * @Route("/api/getStatutsCommandes", name="get_statutsCommandes", methods={"GET"})
     */
    public function getStatutsCommandes(){
        $statuts = $this->statutCommandeRepository->findAll();
        $statutsJson = $this->serializer->serialize($statuts,'json');
        return new JsonResponse($statutsJson,Response::HTTP_OK,[],true);
    }



}