<?php

namespace App\Controller\Admin;

use App\Entity\Eleve;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Classe;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use App\Repository\EleveRepository;


class EleveCrudController extends AbstractCrudController
{

    private EntityManagerInterface $entityManager;
    private EleveRepository $eleveRepository;
public function __construct(EntityManagerInterface $entityManager, EleveRepository $eleveRepository)
{
    $this->entityManager = $entityManager;
    $this->eleveRepository = $eleveRepository;
   

}
    public static function getEntityFqcn(): string
    {
        return Eleve::class;
    }
 public function configureAssets(Assets $assets): Assets
{
    return $assets
        ->addJsFile('build/js/dependent-select.js');
}

public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Eleve) return;

        // Génère le CIN uniquement s’il est vide
        if (!$entityInstance->getCin()) {
            $matricule = $entityInstance->getMatricule();

            if ($matricule && strlen($matricule) >= 4) {
                $baseCin = substr($matricule, -4);
                $cinCandidate = $baseCin;

                // Vérifie l'unicité du CIN
                while ($this->eleveRepository->findOneBy(['cin' => $cinCandidate])) {
                    $cinCandidate = strval(random_int(1000, 9999));
                }

                $entityInstance->setCin($cinCandidate);
            }
        }

        parent::persistEntity($entityManager, $entityInstance);
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            
            TextField::new('matricule', 'Matricule')
                ->setRequired(true)
                ->setHelp('Le matricule doit être unique pour chaque élève.'),
            IdField::new('cin', 'CIN')
                ->setFormTypeOption('disabled', true),
            TextField::new('nom_prenom', 'Nom et Prénom'),
            choiceField::new('sexe', 'Sexe')
                ->setChoices([
                    'Masculin' => 'M',
                    'Féminin' => 'F',
                ])
                ->renderAsNativeWidget()
                ->setRequired(true),
            AssociationField::new('lycee')
                ->setLabel('Lycée'),
            AssociationField::new('classe')
                ->setFormTypeOption('choices', $this->getAllClasses())
                ->setFormTypeOption('choice_label', 'nomClasse')
                ->setRequired(true),
        ];
    }

    private function getAllClasses(): array
{
    return $this->entityManager
        ->getRepository(Classe::class)
        ->findAll();
}
    
}