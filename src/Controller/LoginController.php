<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils; #pr parametrer les errs
final class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils ): Response
    {
        if($this->getUser()) return $this->redirectToRoute("app_dashboard");
        $error=$authenticationUtils->getLastAuthenticationError();
        if($error){
            $this->addFlash('error','identifiant error');
        }
        return $this->render('login/index.html.twig',['error '=>$error]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
    }
}
