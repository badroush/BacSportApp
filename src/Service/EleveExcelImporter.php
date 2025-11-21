<?php
namespace App\Service;

use App\Entity\Eleve;
use App\Entity\Classe;
use App\Entity\Lycee;
use App\Entity\Dispense;
use App\Entity\Epreuve;
use App\Entity\EpreuveBac;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;

class EleveExcelImporter
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function importFromFile(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $header = array_shift($rows); // ignore la ligne des titres
        $eleveRepository = $this->em->getRepository(Eleve::class);
        $count = 0;
        $trrv=0;
        foreach ($rows as $row) {
            // Récupérer les entités Classe et Lycee à partir de l'ID
                $classeId = $row[3] ?? null;
                $lyceeId = $row[4] ?? null;

                // Vérifie que les ID ne sont pas vides
                if (empty($classeId) || empty($lyceeId)) {
                    continue; // ignorer la ligne si les IDs sont manquants
                }

                $classe = $this->em->getRepository(Classe::class)->find($classeId);
                $lycee = $this->em->getRepository(Lycee::class)->find($lyceeId);

                // Vérifie que les entités existent
                if (!$classe || !$lycee) {
                    continue;
                }

            $eleve = new Eleve();
            $matricule = $row[0];
            $cin = null;
            // tester si le matricule est deja eutiliser
            $eleveRepository = $this->em->getRepository(Eleve::class);
            $eleve = $eleveRepository->findOneBy(['matricule' => $matricule]);
            if($eleve){
            $trrv++;
            }else
            {
            $eleve = new Eleve(); // ✅ créer un nouvel objet ici
            $dispense = new Dispense();
            $eprbac= new EpreuveBac();
            if ($matricule && strlen($matricule) >= 4) {
                $baseCin = substr($matricule, -4);
                $cinCandidate = $baseCin;

                // Vérifie l'unicité du CIN dans la base
                while ($eleveRepository->findOneBy(['cin' => $cinCandidate])) {
                    $cinCandidate = strval(random_int(1000, 9999));
                }

                $cin = $cinCandidate;
            }
            $dispense->setEleve($eleve);
            $dispense->setState('present');
            // ajouter l'preuve bac numero 12 a ctte eleve dans la table epreuve_bac
            $eprbac->setEleve($eleve);
            $eprbac->setEpreuve($this->em->getRepository(Epreuve::class)->find(12));
            $eleve->setCin($cin);
            $eleve->setMatricule($matricule);
            $eleve->setNomPrenom($row[1]);
            $eleve->setSexe($row[2]);
            $eleve->setClasse($classe);
            $eleve->setLycee($lycee);
            $this->em->persist($eprbac);
            $this->em->persist($dispense);
            $this->em->persist($eleve);
            $count++;
        }
    }
        $this->em->flush();
        return ['imported' => $count, 'ignored'=>$trrv];
    }
}