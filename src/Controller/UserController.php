<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Dom\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
final class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(), // Supposons que findAll() est utilisé
        ]);
    }

    #[Route('/user/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(User $user, EntityManagerInterface $entityManager, Request $request, UserRepository $userRepository): Response
    {
        if ($request->isMethod('POST')) {
                $user->setName($request->request->get('nom'));
                $user->setPrenom($request->request->get('prenom'));
                $user->setAdresse($request->request->get('adresse'));
                $user->setRoles($request->request->all('roles')); 

                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('app_user'); // Redirection après modification
            
        }
        $roles=$userRepository->findAllRoles();
        return $this->render('useredit/index.html.twig',['user' => $user, 'roles' => $roles]);
    }

    #[Route('/api/user', name: 'app_user_api', methods:['GET'])]
    public function listUsers( UserRepository $userRepository): JsonResponse
    {
        $users=$userRepository->FindAll();
        $data=[];

        foreach($users as $user){
          $data[] =[
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'role' =>$user->getRoles(),
            'First_Name'=> $user->getName(),
            'Last_Name' => $user->getPrenom(),
          ];
        }
        return $this->json($data);
    }
    
    #[Route('/api/user/{id}', name: 'app_user_api_id', methods:['PUT'])]
    public function modifUsers(int $id, Request $request, UserRepository $userRepository): JsonResponse
    {
        $user=$userRepository->find($id);
        if(!$user){
            return $this->json(['message'=>'Utilisateur Non trouve'],400);
        }
        
        $data=json_decode($request->getcontent(),true);
        if(isset($data['nom']))  $user->setName($data['nom']);
        if(isset($data['prenom']))  $user->setPrenom($data['prenom']);
        if(isset($data['adresse']))  $user->setAdresse($data['adresse']);
        if(isset($data['roles']))  $user->setRoles($data['roles']);
       
        return $this->json("mise a jour avec success");
    }
}
