<?php

namespace App\Form;

use App\Entity\Trips;
use App\Entity\Users;
use App\Entity\Vehicle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tripDate', null, [
                'widget' => 'single_text',
            ])
            ->add('origin')
            ->add('destination')
            ->add('mileage')
            ->add('unit')
            ->add('context')
            ->add('category')
            ->add('description')
            ->add('billableClient')
            ->add('Users', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'id',
            ])
            ->add('Vehicle', EntityType::class, [
                'class' => Vehicle::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trips::class,
        ]);
    }
}
