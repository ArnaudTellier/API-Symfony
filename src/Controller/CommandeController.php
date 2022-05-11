<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Terrain;
use App\Repository\ArticleRepository;
use App\Repository\AvantProjetRepository;
use App\Repository\CommandeRepository;
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

class CommandeController extends AbstractController{
    private CommandeRepository $commandeRepository;
    private SerializerInterface $serializer;
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;

    public function __construct( CommandeRepository $commandeRepository,
                                 SerializerInterface $serializer,
                                 EntityManagerInterface $entityManager,
                                 ValidatorInterface $validator)
    {
        $this->commandeRepository = $commandeRepository;
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->validator = $validator;

    }
    /**
     * @Route("/api/commandes", name="get_commandes", methods={"GET"})
     */
    public function getCommandes(){
        $commandes = $this->commandeRepository->findAll();
        $commandesJson = $this->serializer->serialize($commandes,'json');
        return new JsonResponse($commandesJson,Response::HTTP_OK,[],true);
    }

    /**
     * @Route("/api/commande/{id}", name="api_commande_getCommandeById", methods={"GET"})
     */
    public function getCommandeById($id)
    {
        $commande = $this->commandeRepository->find($id);
        $commandeJson = $this->serializer->serialize($commande,'json');
        return new JsonResponse($commandeJson,Response::HTTP_OK,[],true);
    }
    
    //update
    /**
     * @Route("/api/commande/{id}", name="api_commande_updateCommande", methods={"PUT"})
     */
    public function updateCommande($id, Request $request)
    {
        $commande = $this->commandeRepository->find($id);
        $commandeJson = $this->serializer->deserialize($request->getContent(), Commande::class, 'json');
        $errors = $this->validator->validate($commandeJson);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new JsonResponse($errorsString, Response::HTTP_BAD_REQUEST, [], true);
        }
    }

}