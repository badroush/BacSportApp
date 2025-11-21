<?php

namespace App\Form;

use App\Entity\Centre;
use App\Entity\Etablissement;
use App\Entity\Lycee;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LyceeTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateEpreuve')
            ->add('type')
            ->add('heureEpreuve')
            ->add('centre', EntityType::class, [
                'class' => Centre::class,
                'choice_label' => 'id',
            ])
            ->add('etablissement', EntityType::class, [
                'class' => Etablissement::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lycee::class,
        ]);
    }
}
