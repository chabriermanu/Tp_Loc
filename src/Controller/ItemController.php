<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Loan;
use App\Form\BorrowsType;
use App\Repository\ItemRepository;
use App\Form\ItemType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\File\UploadedFile;  
use App\Service\FileUploader;   

final class ItemController extends AbstractController
{
    #[Route('/item', name: 'app_item')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $items = $entityManager->getRepository(Item::class)->findAll();
        
        return $this->render('item/index.html.twig', [
            'items' => $items,
        ]);
    }
    
    #[Route('/item/{id}', name: 'app_item_show')]
    public function show(Item $item, Request $request, EntityManagerInterface $em): Response
    {
        $loanForm = null;
        
        // Si l'utilisateur n'est pas le propriétaire, créer le formulaire
        if ($this->getUser() && $this->getUser() !== $item->getIdUser()) {
            $loan = new Loan();
            $loan->setItem($item);
            $loan->setIdUser($this->getUser());
            
            $loanForm = $this->createForm(BorrowsType::class, $loan);
            $loanForm->handleRequest($request);
            
            if ($loanForm->isSubmitted() && $loanForm->isValid()) {
                $em->persist($loan);
                $em->flush();
                
                $this->addFlash('success', 'Votre demande d\'emprunt a été envoyée !');
                return $this->redirectToRoute('app_item_show', ['id' => $item->getId()]);
            }
        }
        
        return $this->render('item/show.html.twig', [
            'item' => $item,
            'loanForm' => $loanForm?->createView(),
        ]);
    }
 
    
    #[Route('/items', name: 'app_item_list_all')]
    public function listAll(ItemRepository $itemRepository): Response
    {
        // Récupère tous les items
        $items = $itemRepository->findAll();            
        return $this->render('item/list_all.html.twig', [
            'items' => $items,
        ]);
    }

    #[Route('/item/{id}/edit', name: 'app_item_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Item $item, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        if ($item->getIdUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous ne pouvez modifier que vos propres outils.');
        }
        
        $oldPicture = $item->getPicture();
        
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $pictureFile */
            $pictureFile = $form->get('picture')->getData();
            
            if ($pictureFile) {
                $pictureFileName = $fileUploader->upload($pictureFile);
                $item->setPicture($pictureFileName);
                
                // Supprimer l'ancienne image
                if ($oldPicture) {
                    $oldFilePath = $fileUploader->getTargetDirectory() . '/' . $oldPicture;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }
            }
            
            $entityManager->flush();

            return $this->redirectToRoute('app_item', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('item/edit.html.twig', [
            'item' => $item,
            'form' => $form,
        ]);
    }

    #[Route('/item/{id}/delete', name: 'app_item_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Item $item, EntityManagerInterface $entityManager): Response
    {
        if ($item->getIdUser() !== $this->getUser()) {
        throw $this->createAccessDeniedException('Vous ne pouvez supprimer que vos propres outils.');
        }
        if ($this->isCsrfTokenValid('delete'.$item->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($item);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_item', [], Response::HTTP_SEE_OTHER);
    }

}
