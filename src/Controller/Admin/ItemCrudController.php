<?php

namespace App\Controller\Admin;

use App\Entity\Item;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ItemCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Item::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(), //Affiche ce champ dans la liste, mais PAS dans les formulaires d'ajout/édition"
            TextField::new('name'),
            TextEditorField::new('description'),
            ImageField::new('picture')
                ->setBasePath('/uploads/items') //<- URL dans le navigateur
                ->setUploadDir('public/uploads/items') // ← Chemin physique
                ->formatValue(function ($value) {
                    return $value ? basename($value) : null;
                })
                ->onlyOnIndex(),
            
            TextField::new('picture')
                ->onlyOnForms(),
            
            // Category APRÈS picture
            AssociationField::new('category'),
            AssociationField::new('idUser'),
        ];
    }



}
