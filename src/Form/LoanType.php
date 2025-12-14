<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\Loan;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class LoanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        

        $builder
            ->add('start', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début'
            ])
            ->add('fin', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin'
            ])
            ->add('item', EntityType::class, [
                'class' => Item::class,
                'choice_label' => 'name', // ✅ Affiche le nom
                'disabled' => true, // ✅ Affiche mais non modifiable
            ])
        ;
    }

        public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Loan::class,
        ]);
    }
}
