<?php

namespace App\Controller;

use App\Entity\Eleve;
use App\Entity\Classe;
use App\Entity\Epreuve;
use App\Form\EleveType;
use App\Entity\Dispense;
use App\Entity\EpreuveBac;
use App\Form\EleveTypeForm;
use App\Entity\ActionControle;
use App\Repository\EleveRepository;
use App\Repository\LyceeRepository;
use App\Service\EleveExcelImporter;
use App\Repository\ClasseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Service\ModeleExcelParClasseGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;




final class ElevesController extends AbstractController
{
    #[Route('/eleves', name: 'app_eleves')]
public function index(Request $request, EntityManagerInterface $em, EleveRepository $eleveRepository): Response
{
    $eleve = new Eleve();
    $dispense = new Dispense();
    $eprbac= new EpreuveBac();

    $form = $this->createForm(EleveTypeForm::class, $eleve, [
    'user' => $this->getUser(), // ou 'etablissement' => $this->getUser()->getEtablissement()
    'mode' => 'create',
]);

    $form->handleRequest($request);
// dd($form);
    if ($form->isSubmitted() && $form->isValid()) {

        // 💡 Génère le CIN si vide
        if (!$eleve->getCin()) {
            $matricule = $eleve->getMatricule();
            // tester si le matricule est deja eutiliser
            $eleveRepository = $em->getRepository(Eleve::class);
            $eleve = $eleveRepository->findOneBy(['matricule' => $matricule]);
            if($eleve){
            $this->addFlash('خطا', 'رقم بطاقة التعريف مستخدم من قبل');
            return $this->redirectToRoute('app_eleves');
            }

            if ($matricule && strlen($matricule) >= 4) {
                $baseCin = substr($matricule, -4);
                $cinCandidate = $baseCin;

                // Vérifie l'unicité du CIN
                while ($eleveRepository->findOneBy(['cin' => $cinCandidate])) {
                    $cinCandidate = strval(random_int(1000, 9999));
                }

                $eleve->setCin($cinCandidate);
            }
        }

        $em->persist($eleve);
        // ajouter la dispense a cette eleve (elevecin et state) dans la table dispense
        $dispense->setEleve($eleve);
        $dispense->setState('present');
        // ajouter l'preuve bac numero 12 a ctte eleve dans la table epreuve_bac
        $eprbac->setEleve($eleve);
        $eprbac->setEpreuve($em->getRepository(Epreuve::class)->find(12));
        $em->persist($eprbac);
        $em->persist($dispense);
        $em->flush();

        return $this->redirectToRoute('app_eleves');
    }
$eleves = $eleveRepository->findAll();

$epreuvesParEleve = [];
$dispensesParEleve = [];

foreach ($eleves as $e) {
    $epreuvesParEleve[$e->getCin()] = $em->getRepository(EpreuveBac::class)->count(['eleve' => $e]);
    $dispense = $em->getRepository(Dispense::class)->findOneBy(['eleve' => $e]);
    $dispensesParEleve[$e->getCin()] = $dispense ? $dispense->getState() : '—';
}
$cntrl = [];
if (!empty($epreuvesParEleve)) {
    $cntrl = $em->getRepository(ActionControle::class)
        ->createQueryBuilder('a')
        ->where('a.actions LIKE :pattern')
        ->setParameter('pattern', '%_eleve')
        ->getQuery()
        ->getResult();
}

//   dd($dispensesParEleve);
    return $this->render('eleves/index.html.twig', [
        'form' => $form->createView(),
        'eleves' => $eleveRepository->findAll(),
        'epreuvesParEleve' => $epreuvesParEleve,
        'dispensesParEleve' => $dispensesParEleve,
        'actions' => $cntrl,
        
    ]);
}

#[Route('/api/eleves/data', name: 'api_eleves_data')]
public function apiElevesData(Request $request, EntityManagerInterface $em): JsonResponse
{
    $start = $request->query->getInt('start', 0);
    $length = $request->query->getInt('length', 10);
    $draw = $request->query->getInt('draw', 1);
    $order = $request->query->all('order')[0] ?? [];
    $columns = $request->query->all('columns');
    $search = $request->query->all('search');
    $searchValue = $search['value'] ?? '';

    $user = $this->getUser();
    $isAdmin = $this->isGranted('ROLE_ADMIN');

    $columns = [
    ['data' => 'actions'],
    ['data' => 'lycee'],
    ['data' => 'classe'],
    ['data' => 'sexe'],
    ['data' => 'nomPrenom'],
    ['data' => 'matricule'],
    ['data' => 'cin'],
];
$order = $request->query->all('order')[0] ?? [];
$columnIndex = $order['column'] ?? 0;
$orderDir = strtoupper($order['dir'] ?? 'DESC');
// Liste des colonnes triables en base (pas d'alias, pas de relations imbriquées ici)
$allowedOrderFields = ['lycee', 'classe', 'sexe', 'nomPrenom', 'matricule', 'cin'];
// Récupérer le nom de la colonne demandée
$orderColumnRaw = $columns[$columnIndex]['data'] ?? 'nomPrenom';
// Assurer que la colonne est autorisée
$orderColumn = in_array($orderColumnRaw, $allowedOrderFields) ? $orderColumnRaw : 'nomPrenom';
    // Base query
    $qb = $em->createQueryBuilder()
        ->select('e', 'l', 'c')
        ->from(Eleve::class, 'e')
        ->leftJoin('e.lycee', 'l')
        ->leftJoin('e.classe', 'c');
    // Appliquer le filtre uniquement si un utilisateur est connecté et que ce n'est pas un admin
        if (!$isAdmin && $user && method_exists($user, 'getEtablissement') && $user->getEtablissement()) {
            $qb->andWhere('l.etablissement = :etab')
            ->setParameter('etab', $user->getEtablissement());
            }
    // Cloner le query builder pour recordsTotal
    $qbCount = clone $qb;
    $recordsTotal = (int) $qbCount->select('COUNT(e.cin)')->getQuery()->getSingleScalarResult();
    // Appliquer la recherche
    if (!empty($searchValue)) {
        $qb->andWhere('e.nomPrenom LIKE :search OR e.matricule LIKE :search OR e.cin LIKE :search')
        ->setParameter('search', '%' . $searchValue . '%');
    }
    // Compter les lignes filtrées
    $qbFiltered = clone $qb;
    $recordsFiltered = (int) $qbFiltered->select('COUNT(e.cin)')->getQuery()->getSingleScalarResult();
    // Pagination et tri
    $qb->orderBy('e.' . $orderColumn, $orderDir)
    ->setFirstResult($start)
    ->setMaxResults($length);
    $eleves = $qb->getQuery()->getResult();
    // Préparer les données
    $epreuveRepo = $em->getRepository(EpreuveBac::class);
    $dispenseRepo = $em->getRepository(Dispense::class);
    
    $data = [];
    $convocationLink='';
    $deleteLink='';
    $epreuveLink='';
    $editLink='';
    $badge='';
    foreach ($eleves as $e) {
        $nbEp = $epreuveRepo->count(['eleve' => $e]);
        if ($e->getClasse() === null || $e->getClasse()->getNum() === null) {
            $num = '—';
        } else {
            $num = $e->getClasse()->getNum();
        }
        $cin = $e->getCin();
        $lycee = $e->getLycee()?->getNom() ?? '—';
        $classe = $e->getClasse()?->getNomClasse()->getNom().' '.$num ?? '—';
        // dd($classe);
        $sexe = $e->getSexe() === 'f' ? 'أنثى' : 'ذكر';
        $nomprenom = $e->getNomPrenom();
        $matricule = $e->getMatricule();
        $cin = $e->getCin();
        $dispense = $dispenseRepo->findOneBy(['eleve' => $e]);
        $state = $dispense ? $dispense->getState() : '--';
        $codeMap = ['--' => '?', 'absent' => 'A', 'dispense' => 'D'];
        $colorMap = ['--' => 'default','present' => 'success', 'absent' => 'danger', 'dispense' => 'warning'];
        $code = $codeMap[$state] ?? 'P';
        $color = $colorMap[$state] ?? 'secondary';
        $cntrl = $em->getRepository(ActionControle::class)
    ->createQueryBuilder('a')
    ->where('a.actions LIKE :pattern')
    ->setParameter('pattern', '%_eleve')
    ->getQuery()
    ->getResult();
        foreach ($cntrl as $actions){
        if (
            ($this->isGranted('ROLE_ADMIN') && $actions->getActions() == 'state_eleve') ||
            ($actions->getActions() == 'state_eleve' && $actions->isActive() && !$actions->isMasque())
        ) {
            $badge = sprintf('<button class="btn btn-%s" disabled>%s</button>', $color, $code);
            
        }
    }
        foreach ($cntrl as $actions){
        if (
            ($this->isGranted('ROLE_ADMIN') && $actions->getActions() == 'addepreuve_eleve') ||
            ($actions->getActions() == 'addepreuve_eleve' && $actions->isActive() && !$actions->isMasque())
        ) {
        $epreuveLink = sprintf(
        '<a href="%s" class="btn btn-outline-info" title="Voir épreuves"><i class="">'. $nbEpreuves = $epreuveRepo->count(['eleve' => $e]).'</i></a>',
        $this->generateUrl('epreuve_bac_ajouter', ['cin' => $cin]) );
        }
    }
    foreach ($cntrl as $actions){
        if (
            ($this->isGranted('ROLE_ADMIN') && $actions->getActions() == 'printconvocation_eleve') ||
            ($actions->getActions() == 'printconvocation_eleve' && $actions->isActive() && !$actions->isMasque() && $nbEp>=3)
        ) {
        $convocationLink = sprintf(
        '<a href="%s" class="btn btn-outline-primary" title="Convocation"
        onclick="window.open(this.href, \'popupConvocation\', \'width=800,height=600,scrollbars=yes,resizable=yes\'); return false;">
        <i class="fas fa-print"></i>
        </a>',
        $this->generateUrl('convocation_pdf', ['cin' => $cin]));
        }elseif ($nbEp<3){
            $convocationLink = "";
        }
    }

    foreach ($cntrl as $actions){
        if (
            ($this->isGranted('ROLE_ADMIN') && $actions->getActions() == 'update_eleve') ||
            ($actions->getActions() == 'update_eleve' && $actions->isActive() && !$actions->isMasque())
        ) {
        $editLink = sprintf(
            '<a href="%s" class="btn btn-outline-success" title="Modifier"><i class="fas fa-pen"></i></a>',
            $this->generateUrl('eleve_edit', ['cin' => $cin]));
        }
    }

foreach ($cntrl as $actions){
        if (
            ($this->isGranted('ROLE_ADMIN') && $actions->getActions() == 'delete_eleve') ||
            ($actions->getActions() == 'delete_eleve' && $actions->isActive() && !$actions->isMasque())
        ) {
        $deleteLink = sprintf(
            '<a href="%s" class="btn btn-outline-danger" title="Supprimer"><i class="fas fa-trash-alt"></i></a>',
            $this->generateUrl('eleve_delete', ['id' => $cin]));
        }
    }
        $data[] = [
            'actions' => $badge.' '.$editLink.' '.$epreuveLink.' '.$deleteLink.' '.$convocationLink ,
            'lycee' => $lycee,
            'classe' => $classe,
            'sexe' => $sexe,
            'nomprenom' => $nomprenom,
            'matricule' => $matricule,
            'cin' => $cin
        ];
    }
$cntrl = [];
if (!empty($data)) {
    $cntrl = $em->getRepository(ActionControle::class)
        ->createQueryBuilder('a')
        ->where('a.actions LIKE :pattern')
        ->setParameter('pattern', '%_eleve')
        ->getQuery()
        ->getResult();
}
    return new JsonResponse([
        'draw' => $draw,
        'recordsTotal' => $recordsTotal,
        'recordsFiltered' => $recordsFiltered,
        'data' => $data,
        'actions' => $cntrl,
    ]);
 
}



#[Route('/eleve/{cin}/edit', name: 'eleve_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, string $cin, EntityManagerInterface $em): Response
{
    $eleve = $em->getRepository(Eleve::class)->find($cin);
    $user = $this->getUser();
    if (!$eleve) {
    $this->addFlash('خطا', 'لا يمكن العثور على التلميذ');
    return $this->redirectToRoute('app_eleves'); 
    }
    if ($eleve->getLycee()->getEtablissement()->getId() !== $user->getEtablissement()->getId() && !$this->isGranted('ROLE_ADMIN')) {
        $this->addFlash('تحذير', 'لا يمكنك التعديل على هذ التلميذ');
          return $this->redirectToRoute('app_eleves'); // ou toute autre page de retour
    }

    $form = $this->createForm(EleveTypeForm::class, $eleve, [
    'user' => $this->getUser(), // ou 'etablissement' => $this->getUser()->getEtablissement()
    'mode' => 'edit',

]);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush();
        $this->addFlash('success', 'لقد تم تعديل التلميذ بنجاح');
        return $this->redirectToRoute('app_eleves'); // ou toute autre page de retour
    }

    return $this->render('eleves/edit.html.twig', [
        'form' => $form->createView(),
        'selectedClasseId' => $eleve->getClasse() ? $eleve->getClasse()->getId() : '',
    ]);
}
#[Route('/eleve/delete/{id}', name: 'eleve_delete')]
public function delete(Eleve $eleve, EntityManagerInterface $em): Response
{
    $em->remove($eleve);
    $em->flush();
    return $this->redirectToRoute('app_eleves');
}

#[Route('/api/classe/{lyceeId}', name: 'api_get_classes', methods: ['GET'])]
public function getClasses(int $lyceeId, ClasseRepository $classeRepository): JsonResponse
{
    $classes = $classeRepository->findBy(['lycee' => $lyceeId]);
    $data = array_map(function ($classe) {
    return [
        'id' => $classe->getId(),
        'nom' => $classe->getNomClasse()->getNom(),
        'num' => $classe->getNum(),
    ];
}, $classes);
    return new JsonResponse($data);
}

#[Route('/convocation/{cin}', name: 'convocation_pdf')]
public function convocation(
    string $cin,
    EleveRepository $eleveRepository
): Response {
    $eleve = $eleveRepository->findOneBy(['cin' => $cin]);

    if (!$eleve) {
        throw $this->createNotFoundException("الطالب غير موجود.");
    }

    $classe = $eleve->getClasse();
    $lycee = $classe->getLycee();
    $centre= $lycee->getCentre();
    $descrp=$centre->getDescription();


    return $this->render('eleves/convocation.html.twig', [
        'nomPrenom' => $eleve->getNomPrenom(),
        'cin' => $eleve->getMatricule(),
        'section' => $classe->getNomClasse(),
        'classe' => $classe->getNomClasse().' '.$classe->getNum(),
        'school' => $lycee->getNom(),
        'delegation' => $lycee->getNom(),
        'center' => $centre.'('.$descrp.')', // valeur statique ou récupérée
        'date' => $lycee->getDateEpreuve()->format('d/m/Y'),
        'time' => $lycee->getHeureEpreuve()->format('H:i'),
        'year' => date('Y'),
        'type' => $lycee->getType(),
    ]);
}
#[Route('/eleves/import', name: 'eleves_import')]
public function import(Request $request, EleveExcelImporter $importer): Response
{
    $file = $request->files->get('excel_file');

    if ($file && $file->isValid()) {
        $filename = uniqid().'.'.$file->guessExtension();
        $filePath = $this->getParameter('kernel.project_dir').'/public/uploads/listexsl/'.$filename;

        try {
            $file->move(dirname($filePath), basename($filePath));
            $result = $importer->importFromFile($filePath);

            $this->addFlash('success', $result['imported'].' تلميذ تم إضافته. و '. $result['ignored'].' تلميذ تم تجاهله.');
        } catch (FileException $e) {
            $this->addFlash('error', 'حدث خطأ في تحميل الملف.');
        }
    } else {
        $this->addFlash('error', 'ملف Excel غير صحيح.');
    }

    return $this->redirectToRoute('app_eleves');
}

#[Route('/eleves/modele/{id}', name: 'modele_excel_par_classe')]
public function telechargerModele(Classe $classe, ModeleExcelParClasseGenerator $generator): Response
{
    $filePath = $generator->genererPourClasse($classe);

    return $this->redirect($filePath); // redirige vers le fichier généré
}


}