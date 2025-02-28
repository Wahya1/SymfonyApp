<?php
namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(UserRepository $userRepository): Response
    {
        $userCount =$userRepository->countUser();
        return $this->render('dashboard/index.html.twig', [
            'userCount' => $userCount,
        ]);
    }
}
