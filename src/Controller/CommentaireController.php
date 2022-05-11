<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Terrain;
use App\Repository\ArticleRepository;
use App\Repository\AvantProjetRepository;
use App\Repository\CommandeRepository;
use App\Repository\CommentaireRepository;
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

class CommentaireController extends AbstractController{
    private CommentaireRepository $CommentaireRepository;
    private SerializerInterface $serializer;
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;

    public function __construct( CommentaireRepository $CommentaireRepository,
                                 SerializerInterface $serializer,
                                 EntityManagerInterface $entityManager,
                                 ValidatorInterface $validator)
    {
        $this->CommentaireRepository = $CommentaireRepository;
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->validator = $validator;

    }
    /**
     * @Route("/api/commentaires", name="get_commentaires", methods={"GET"})
     */
    public function getCommentaires(){
        $commentaires = $this->CommentaireRepository->findAll();
        $commentairesJson = $this->serializer->serialize($commentaires,'json');
        return new JsonResponse($commentairesJson,Response::HTTP_OK,[],true);
    }



}