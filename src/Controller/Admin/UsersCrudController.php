<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class UsersCrudController extends AbstractCrudController
{
private UserPasswordHasherInterface $passwordHasher;
    private RequestStack $requestStack;

    public function __construct(UserPasswordHasherInterface $passwordHasher, RequestStack $requestStack)
    {
        $this->passwordHasher = $passwordHasher;
        $this->requestStack = $requestStack;
    }

    public static function getEntityFqcn(): string
    {
        return Users::class;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Users) {
           $data = $this->requestStack->getCurrentRequest()->request->all();
            $usersData = $data['Users'] ?? [];
            $plainPassword = $usersData['password'] ?? null;
            // dd($plainPassword);
            if ($plainPassword) {
                $hashedPassword = $this->passwordHasher->hashPassword($entityInstance, $plainPassword);
                $entityInstance->setPassword($hashedPassword);
            }
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Users) {
            $request = $this->requestStack->getCurrentRequest();
            $plainPassword = $request->request->get('User[password]'); // Ajuste selon ton formulaire EasyAdmin

            if ($plainPassword) {
                $hashedPassword = $this->passwordHasher->hashPassword($entityInstance, $plainPassword);
                $entityInstance->setPassword($hashedPassword);
            }
        }

        parent::updateEntity($entityManager, $entityInstance);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('email'),
            TextField::new('user'),
            AssociationField::new('etablissement'),
            TextField::new('mobile'),
            TextField::new('cnrps'),

            // Champ mot de passe non mappé
             TextField::new('password')
                ->setFormTypeOption('mapped', false) // Ne pas mapper ce champ à l'entité
                ->setRequired($pageName === 'new') // Requis uniquement lors de la création
                ->setHelp('Laissez vide pour ne pas changer le mot de passe')
                ->onlyOnForms(),

            ImageField::new('photo')
                ->setBasePath('uploads/photoprofile/')
                ->setUploadDir('public/uploads/photoprofile/')
                ->setSortable(false),

            ChoiceField::new('roles')
                ->setChoices([
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                    'SousAgent' => 'ROLE_SOUSAGENT',
                ])
                ->allowMultipleChoices()
                ->renderExpanded(),

            ChoiceField::new('etat')
                ->setChoices([
                    'actif' => 'actif',
                    'freezed' => 'freezed',
                ])
        ];
    }
}