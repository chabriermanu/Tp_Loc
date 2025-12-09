<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/mon-compte', name: 'app_user_account')]
    #[IsGranted('ROLE_USER')]
    public function account(): Response
    {
        $user = $this->getUser();
        
        return $this->render('user/account.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/mon-compte/modifier', name: 'app_user_edit')]
    #[IsGranted('ROLE_USER')]
    public function editProfile(): Response
    {
        $user = $this->getUser();
        
        return $this->render('user/edit.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/mes-outils', name: 'app_user_tools')]
    #[IsGranted('ROLE_USER')]
    public function myTools(): Response
    {
        $user = $this->getUser();
        // Récupérer les outils que l'utilisateur met en prêt
        
        return $this->render('user/my_tools.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/mes-emprunts', name: 'app_user_borrows')]
    #[IsGranted('ROLE_USER')]
    public function myBorrows(): Response
    {
        $user = $this->getUser();
        // Récupérer les outils que l'utilisateur a empruntés
        
        return $this->render('user/my_borrows.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/mes-prets', name: 'app_user_loans')]
    #[IsGranted('ROLE_USER')]
    public function myLoans(): Response
    {
        $user = $this->getUser();
        // Récupérer les prêts en cours (ses outils prêtés à d'autres)
        
        return $this->render('user/my_loans.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/mes-favoris', name: 'app_user_favorites')]
    #[IsGranted('ROLE_USER')]
    public function myFavorites(): Response
    {
        $user = $this->getUser();
        // Outils qu'il veut emprunter plus tard
        
        return $this->render('user/favorites.html.twig', [
            'user' => $user,
        ]);
    }
}