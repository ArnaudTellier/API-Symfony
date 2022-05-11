<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Terrain;
use App\Repository\ArticleRepository;
use App\Repository\AvantProjetRepository;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RoleController extends AbstractController{
    private RoleRepository $roleRepository;
    private SerializerInterface $serializer;
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;

    public function __construct( RoleRepository $roleRepository,
                                 SerializerInterface $serializer,
                                 EntityManagerInterface $entityManager,
                                 ValidatorInterface $validator)
    {
        $this->roleRepository = $roleRepository;
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->validator = $validator;

    }
    /**
     * @Route("/api/getRoles", name="get_roles", methods={"GET"})
     */
    public function getRoles(){
        $role = $this->roleRepository->findAll();
        $roleJson = $this->serializer->serialize($role,'json');
        return new JsonResponse($roleJson,Response::HTTP_OK,[],true);
    }
    public function getRoleById($id){
        $role = $this->roleRepository->find($id);
        return $role;



    }

}