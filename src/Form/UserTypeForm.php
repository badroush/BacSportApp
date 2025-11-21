<?php

namespace App\Form;

use App\Entity\Etablissement;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class UserTypeForm extends AbstractType
{
public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user') // nom complet
            ->add('email')
            ->add('mobile', null, [
                'attr' => [
                    'autocomplete' => 'tel', // ou 'username' si c’est un identifiant
                    'class' => 'form-control',
                    'dir' => 'rtl',
                    'maxlength' => 20
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => false,
                'mapped' => false,
                'invalid_message' => '⚠ كلمتا العبور غير متطابقتين.',
                'first_options'  => ['label' => 'كلمة العبور',
                    'attr' => ['class' => 'form-control', 'autocomplete' => 'new-password']],
                'second_options' => ['label' => 'تأكيد كلمة العبور',
                    'attr' => ['class' => 'form-control', 'autocomplete' => 'new-password']]
            ])
            ->add('photo', FileType::class, [
                'label' => '',
                'mapped' => false,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
