<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Loan;
use App\Form\ItemType;
use App\Repository\LoanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\FileUploader;

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

    #[Route('/mes-outils', name: 'app_user_tools', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function myTools(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $user = $this->getUser();
        
        // Création du formulaire pour la modale
        $item = new Item();
        $item->setIdUser($user);
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        // Si le formulaire est soumis, on enregistre directement
        if ($form->isSubmitted() && $form->isValid()) {
             /** @var UploadedFile $brochureFile */
            $pictureFile = $form->get('picture')->getData();
            if ($pictureFile) {
                $pictureFileName = $fileUploader->upload($pictureFile);
                $item->setPicture($pictureFileName);
            }
            $entityManager->persist($item);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_user_tools', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('user/my_tools.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);
    }
    #[Route('/mes-emprunts', name: 'app_user_borrows')]
    #[IsGranted('ROLE_USER')]
    public function myBorrows(LoanRepository $loanRepository): Response
    {
        $user = $this->getUser();
        
        return $this->render('user/my_borrows.html.twig', [
            'user' => $user,
            'loans' => $loanRepository->findByBorrower($user), 
        ]);
    }

    #[Route('/mes-prets', name: 'app_user_loans')]
    #[IsGranted('ROLE_USER')]
    public function myLoans(LoanRepository $loanRepository): Response
    {
        // Récupère les prêts où cet utilisateur est le prêteur
        $user = $this->getUser();
        
        return $this->render('user/my_loans.html.twig', [
            'user' => $user,
            'loans' => $loanRepository->findByItemOwner($user),
        ]);
    }

    #[Route('/loan/{id}/mark-returned', name: 'loan_mark_returned', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function markAsReturned(
        Loan $loan,
        EntityManagerInterface $em
    ): Response {
        $user = $this->getUser();

        // Vérifier que c'est bien le propriétaire de l'item
        if ($loan->getItem()->getIdUser() !== $user) {
            $this->addFlash('danger', 'Vous ne pouvez pas marquer ce prêt comme restitué.');
            return $this->redirectToRoute('app_user_loans');
        }

        // Vérifier que le prêt n'est pas déjà terminé
        if ($loan->getStatus() === 'completed') {
            $this->addFlash('warning', 'Ce prêt est déjà marqué comme restitué.');
            return $this->redirectToRoute('app_user_loans');
        }

        // Marquer comme restitué
        $loan->markAsReturned();
        $em->flush();

        $this->addFlash('success', 'Le prêt a été marqué comme restitué avec succès !');
        return $this->redirectToRoute('app_user_loans');
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