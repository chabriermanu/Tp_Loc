<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ItemController extends AbstractController
{
    #[Route('/item', name: 'app_item')]
    public function index(): Response
    {
        return $this->render('item/index.html.twig', [
            'controller_name' => 'ItemController',
        ]);
    }
    #[Route('/item/new', name: 'app_item_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]

    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $item = new Item();
        $item->setIdUser($this->getUser());
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('app_item', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('item/new.html.twig', [
            'item' => $item,
            'form' => $form,
        ]);
    }
       #[Route('/item/{id}', name: 'app_item_show', methods: ['GET'])]
    public function show(Item $item): Response
    {
        return $this->render('item/show.html.twig', [
            'item' => $item,
        ]);
    }
    #[Route('/item/{id}/edit', name: 'app_item_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Item $item, EntityManagerInterface $entityManager): Response
    {
        if ($item->getIdUser() !== $this->getUser()) {
        throw $this->createAccessDeniedException('Vous ne pouvez modifier que vos propres outils.');
        }
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
