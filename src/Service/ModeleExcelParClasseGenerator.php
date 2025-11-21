<?php
namespace App\Service;

use App\Entity\Classe;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ModeleExcelParClasseGenerator
{
    private string $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    public function genererPourClasse(Classe $classe): string
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // En-têtes
        $sheet->fromArray(['Matricule', 'Nom', 'Sexe (h/f)', 'Classe ID', 'Lycée ID'], NULL, 'A1');

        // Valeur fixe de classe et lycée
        $sheet->setCellValue('D2', $classe->getId());
        $sheet->setCellValue('E2', $classe->getLycee()->getId());

        // Enregistre le fichier dans /public/uploads/modele
        $dir = $this->projectDir . '/public/uploads/modele';
        if (!is_dir($dir)) mkdir($dir, 0775, true);

        $filename = 'modele_classe_' . $classe->getId() . '.xlsx';
        $filepath = $dir . '/' . $filename;

        $writer = new Xlsx($spreadsheet);
        $writer->save($filepath);

        // Retourne le chemin web pour téléchargement
        return '/uploads/modele/' . $filename;
    }
}
