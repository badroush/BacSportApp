<?php

namespace App\Form;

use App\Entity\Classe;
use App\Entity\Eleve;
use App\Entity\Lycee;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Repository\LyceeRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Users;


class EleveTypeForm extends AbstractType
{

    private $lyceeRepository;

    public function __construct(LyceeRepository $lyceeRepository)
    {
        $this->lyceeRepository = $lyceeRepository;

    }
   public function buildForm(FormBuilderInterface $builder, array $options,)
{
    $lyceeChoices = [];
    $user = $options['user'];
    $lyceeChoices = $this->lyceeRepository->findByUserEtablissement($user);
    $isEdit = $options['mode'] === 'edit';

    $builder
        ->add('cin', TextType::class, [
            'attr' => [
                'class' => 'form-control',
                 'dir' => 'rtl',
                'style' => $isEdit ? 'display: none;' : 'display: block;',],
        ])
        ->add('nomPrenom')
        ->add('sexe', ChoiceType::class, [
            'choices' => ['ذكر' => 'h', 'أنثى' => 'f'],
            'placeholder' => 'اختر الجنس',
            'attr' => ['class' => 'form-select', 'dir' => 'rtl'],
        ])
        ->add('matricule', TextType::class, [
            'attr' => [
                'class' => 'form-control',
                 'dir' => 'rtl',
                'style' => $isEdit ? 'display: none;' : 'display: block;',
                ],
        ]);
        $builder->add('lycee', EntityType::class, [
            'class' => Lycee::class,
            'choices' => $lyceeChoices,
            'choice_label' => 'etablissement',
            'placeholder' => 'اختر المعهد',
            'attr' => ['class' => 'form-select'],
        ])
        ->add('classe', EntityType::class, [
            'class' => Classe::class,
            'choice_label' => 'nomClasse',
            'placeholder' => 'اختر القسم',
            'attr' => ['class' => 'form-select'],
            'required' => true,
        ]);
}

public function configureOptions(OptionsResolver $resolver)
{
    $resolver->setRequired('user');  // On force la présence de 'user' dans options
    $resolver->setDefaults([
        'data_class' => Eleve::class,
        'mode' => 'create', // valeur par défaut
    ]);
}
}