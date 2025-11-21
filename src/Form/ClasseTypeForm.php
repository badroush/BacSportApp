<?php

namespace App\Form;

use App\Entity\Classe;
use App\Entity\Lycee;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\LyceeRepository;
use App\Entity\Users;
use App\Entity\NomClasse;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;



class ClasseTypeForm extends AbstractType
{
    private $lyceeRepository;

    public function __construct(LyceeRepository $lyceeRepository)
    {
        $this->lyceeRepository = $lyceeRepository;

    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $lyceeChoices = [];
        $user = $options['user'];
            $lyceeChoices = $this->lyceeRepository->findByUserEtablissement($user);

    $builder
    ->add('nomClasse', EntityType::class, [
        'class' => NomClasse::class,
        'choice_label' => 'nom', // ou un autre champ si différent
        'placeholder' => 'اختر اسم القسم',
        'attr' => ['class' => 'form-select']
    ])
    ->add('lycee', EntityType::class, [
        'class' => Lycee::class,
        'choices' => $lyceeChoices,
        'choice_label' => 'etablissement',
        'placeholder' => 'اختر المعهد',
        'attr' => ['class' => 'form-select'],
    ])
    ->add('num', ChoiceType::class, [
        'choices' => array_combine(range(1, 20), range(1, 20)), // Génère une liste 1 => 1, 2 => 2, ...
        'placeholder' => 'اختر رقم القسم', // Option vide par défaut
        'required' => false,
        'attr' => ['class' => 'form-select']
    ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        'data_class' => Classe::class,
        'user' => null, // ici on déclare que l’option "user" est permise
        ]);
    }
}