<?php

namespace App\Controller\Admin;

use App\Entity\EpreuveBac;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use App\Controller\Admin\EleveCrudController;
use App\Controller\Admin\ClasseCrudController;

class EpreuveBacCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EpreuveBac::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
       return [
        IdField::new('id')->hideOnForm(),

        // Association avec l'élève
        AssociationField::new('eleve')
            ->setLabel('Élève')
            ->setCrudController(EleveCrudController::class)
            ->autocomplete(),
        // Association avec l’épreuve (ou classe ? attention au nom du champ)
        AssociationField::new('epreuve')
            ->setLabel('Épreuve')
            ->setCrudController(EpreuveCrudController::class) // corrigé ici : EpreuveCrudController au lieu de ClasseCrudController
            ->autocomplete(),
    ];
    }
   
}
