<?php

namespace App\Form;

use App\Entity\Eleve;
use App\Entity\Epreuve;
use App\Entity\OperationBac;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use App\Entity\Bareme; // Assurez-vous d'importer l'entité Bareme si nécessaire
use Symfony\Component\Form\Extension\Core\Type\TextType;


class OperationBacTypeForm extends AbstractType
{
   public function buildForm(FormBuilderInterface $builder, array $options)
    {
    $epreuveChoices = $options['epreuves'];

        $builder
            ->add('epreuve', EntityType::class, [
                'class' => Epreuve::class,
                'choices' => $epreuves,
                'choice_label' => 'nom',
                'label' => 'Épreuve',
            ])
            ->add('resultat', ChoiceType::class, [
                'choices' => [
                    'Absent' => 'A',
                    'Présent' => 'P',
                    'Dispensé' => 'D',
                    'Autre' => 'Autre' // à adapter
                ],
                'label' => 'Résultat',
            ])
            ->add('par', HiddenType::class, [
                'data' => 'Admin', // ou user actuel si tu veux enregistrer l'auteur
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OperationBac::class,
            'epreuves' => [],
        ]);
    }
}
