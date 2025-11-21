<?php

namespace App\Controller\Admin;

use App\Entity\Lycee;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField as associateField;

class LyceeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Lycee::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            textField::new('nom', 'Nom du Lycée'),
            associateField::new('centre'),
            associateField::new('etablissement'),
            ChoiceField::new('type', 'Type de Lycée')
            ->setChoices([
                'Publique' => 'PUBLIQUE',
                'Privé' => 'PRIVE',
            ])
            ->renderAsNativeWidget()
            ->setRequired(true),
            dateField::new('date_epreuve', 'Date de l\'épreuve')
                ->setFormat('dd-MM-yyyy')
                ->setRequired(false),
            timeField::new('heure_epreuve', 'Heure de l\'épreuve')
                ->setFormat('HH:mm')
                ->setRequired(false),
            
        ];
    }
    
}
