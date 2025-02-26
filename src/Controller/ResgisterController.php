<?php
    
namespace App\Controller;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class ResgisterController extends AbstractController
{
    #[Route('/favicon.ico', name: 'favicon')]
    public function favicon(): Response
    {
        return new Response('', Response::HTTP_NO_CONTENT);
    }


    #[Route('/resgister', name: 'app_resgister')]
    public function Register(Request $request, EntityManagerInterface $entityManagerInterface,UserPasswordHasherInterface $userPasswordHasher): Response
    {
        echo("in the register controller");
        if($request->isMethod("POST")){
            $email=$request->get("email");
            $nom=$request->get("nom");  

            $password=$request->get("password");
            $adresse=$request->get("adresse");
            $prenom=$request->get("prenom");
            $user =new User();
            $user->setEmail($email);
            $user->setPassword($password);
            $user->setName($nom);
            $user->setAdresse($adresse); 
            echo($adresse);
            $user->setPrenom($prenom);
            $user->setRoles(["ROLE_USER"]);
            $hashedPassword=$userPasswordHasher->hashPassword($user,$password);
            $user->setPassword($hashedPassword);
           
             
            $entityManagerInterface->persist($user);
            $entityManagerInterface->flush();
            return $this->redirectToRoute("app_login");

        }
        return $this->render('resgister/index.html.twig', [
            'controller_name' => 'ResgisterController',
        ]);
    }
}
