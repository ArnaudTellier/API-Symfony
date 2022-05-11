<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Employe;
use App\Entity\PermisConstruire;
use App\Entity\Rubrique;
use App\Entity\Terrain;
use App\Repository\ArticleRepository;
use App\Repository\AvantProjetRepository;
use App\Repository\EmployeRepository;
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
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header("Content-type: application/json; charset=UTF8");
class EmployeController extends AbstractController{
    private EmployeRepository $employeRepository;
    private RoleController $roleController;
    private SerializerInterface $serializer;
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;

    public function __construct( EmployeRepository $employeRepository,
                                 SerializerInterface $serializer,
                                 RoleController $roleController,
                                 EntityManagerInterface $entityManager,
                                 ValidatorInterface $validator)
    {
        $this->employeRepository = $employeRepository;
        $this->serializer = $serializer;
        $this->roleController = $roleController;
        $this->entityManager = $entityManager;
        $this->validator = $validator;

    }
    /**
     * @Route("/api/employes", name="get_employes", methods={"GET"})
     */
    public function getEmployes(){
        $employes = $this->employeRepository->findAll();
        $employesJson = $this->serializer->serialize($employes,'json');
        return new JsonResponse($employesJson,Response::HTTP_OK,[],true);
    }
 /**
     * @Route("/api/employe/{id}", name="get_employebyid", methods={"GET"})
     */
    public function getEmployeById($id){
        $employe = $this->employeRepository->find($id);

        return $employe;
    }

    /**
     * @Route("/api/employe/add", name="api_employe_add", methods={"POST"})
     */
    public function addEmploye(Request $request)
    {
        try {
            $employeJson = $request->getContent();
            $employeTT = json_decode($employeJson, true);


            $role = null;
            if (isset($employeTT['id_role'])){
                $role = $this->roleController->getRoleById($employeTT['id_role']);
            }
            $employe = new Employe();
            $employe->setIdRole($role);
            $employe->setNom($employeTT['nom']);
            $employe->setPrenom($employeTT['prenom']);
            $employe->setMotDePasse($employeTT['mot_de_passe']);
            $employe->setMail($employeTT['mail']);
            $employe->setLibRue($employeTT['lib_rue']);
            $employe->setCpVille($employeTT['CP_ville']);
            $employe->setVille($employeTT['ville']);
            $employe->setTel($employeTT['tel']);
            $employe->setDateEmbauche($employeTT['date_embauche']);



            // Enregistrer l'objet $banque dans la base de données
            $this->entityManager->persist($employe); // Préparer l'ordre INSERT
            $this->entityManager->flush(); // Envoyer l'ordre INSERT vers la base de données
            // Renvoyer une réponse HTTP
            $employeJson = $this->serializer->serialize($employe,'json');
            return new JsonResponse($employeJson,Response::HTTP_CREATED,[],true);
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

    private function validatePermisConstruire(Employe $employe) : ?Response {
        $errors = $this->validator->validate($employe);
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
     * @Route("/api/employe/{id}", name="api_employe_update", methods={"PUT"})
     */
    public function updateEmploye(Request $request,$id)
    {
        $employeJson = $request->getContent();
        $employe = $this->employeRepository->find($id);
        $this->serializer->deserialize($employeJson,Employe::class,"json",['object_to_populate'=>$employe]);
        $this->entityManager->flush();
        return new JsonResponse("Bon",Response::HTTP_OK,[] ,true);
    }

    /**
     * @Route("/api/redacteurs", name="get_redacteurs", methods={"GET"})
     */
    public function getRedacteurs(){
        $employes = $this->employeRepository->findBy(array('idRole' => 2)); // 2 = id du role redacteur
        $employesJson = $this->serializer->serialize($employes,'json');
        return new JsonResponse($employesJson,Response::HTTP_OK,[],true);
    }



}