<?php

namespace App\Controller\Admin;

use App\Entity\Loan;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class LoanCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Loan::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Prêt')
            ->setEntityLabelInPlural('Prêts')
            ->setDefaultSort(['start' => 'DESC'])
            ->setPageTitle('index', 'Gestion des prêts')
            ->setPageTitle('new', 'Créer un prêt')
            ->setPageTitle('edit', 'Modifier le prêt')
            ->setPageTitle('detail', 'Détails du prêt');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            
            AssociationField::new('item', 'Outil emprunté')
                ->setRequired(true)
                ->formatValue(function ($value, $entity) {
                    return $value ? $value->getName() : '-';
                }),
            
            AssociationField::new('idUser', 'Emprunteur')
                ->setRequired(true)
                ->formatValue(function ($value, $entity) {
                    return $value ? $value->getEmail() : '-';
                }),
            
            DateField::new('start', 'Date de début')
                ->setRequired(true)
                ->setFormat('dd/MM/yyyy'),
            
            DateField::new('fin', 'Date de fin')
                ->setRequired(true)
                ->setFormat('dd/MM/yyyy'),
            
            IntegerField::new('duration', 'Durée (jours)')
                ->onlyOnIndex()
                ->onlyOnDetail()
                ->setTemplatePath('admin/field/duration.html.twig'), // Optionnel pour un affichage personnalisé
        ];
    }
}