<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $category = $entityManager->getRepository(Category::class)->findAll();
        
        return $this->render('category/index.html.twig', [
            'categories' => $category,
        ]);
    }   
   #[Route('/category/{id}', name: 'app_category_show', methods: ['GET'])]
        public function show(Category $category, ItemRepository $itemRepository): Response
    {
          $items = $itemRepository->findBy(['category' => $category]);
        // Symfony trouve automatiquement la catégorie par l'id
        // et lance une 404 si elle n'existe pas
        
        return $this->render('category/show.html.twig', [
            'category' => $category,
            'items' => $items,
        ]);
    }

}
