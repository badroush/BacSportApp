<?php

namespace App\Form;

use App\Entity\Eleve;
use App\Entity\Epreuve;
use App\Entity\EpreuveBac;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EpreuveBacTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       $builder
            ->add('eleve', HiddenType::class, [
                'mapped' => false, // pour lier à l'entité
            ])
            // ->add('eleve', EntityType::class, [
            //     'class' => Eleve::class,
            //     'choice_label' => function (Eleve $eleve) {
            //         return $eleve->getNomPrenom() . ' - ' . $eleve->getMatricule();
            //     },
            //     'attr' => ['class' => 'form-select'],
            //     'label' => 'التلميذ',
            // ])
            ->add('epreuve', EntityType::class, [
                'class' => Epreuve::class,
                'choice_label' => 'libelle',
                'label' => 'الإختبار',
                'placeholder' => 'اختر الإختبار',
                'required' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EpreuveBac::class,
        ]);
    }
}
