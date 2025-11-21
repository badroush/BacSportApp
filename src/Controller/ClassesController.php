<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Lycee;
use App\Entity\Classe;
use App\Form\LyceeTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ClasseRepository;
use App\Form\ClasseTypeForm;
use App\Entity\Eleve;
use App\Repository\EleveRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\EpreuveRepository;
use App\Repository\NoteRepository;
use App\Repository\LyceeRepository;
use App\Repository\OperationBacRepository;
use App\Entity\Epreuve;
use App\Entity\EpreuveBac;
use App\Entity\ActionControle;
use App\Entity\Dispense;



final class ClassesController extends AbstractController
{
private $classeRepository;

    public function __construct(ClasseRepository $classeRepository)
    {
        $this->classeRepository = $classeRepository;
    }
    #[Route('/classes', name: 'app_classes')]
    public function index(Request $request, EntityManagerInterface $em, ClasseRepository $classeRepository, EleveRepository $eleveRepository): Response
{
    if (!$this->getUser()) {
        throw $this->createAccessDeniedException('Vous devez être connecté.');
    }
$cntrl = $em->getRepository(ActionControle::class)
    ->createQueryBuilder('a')
    ->where('a.actions LIKE :pattern')
    ->setParameter('pattern', '%_classe')
    ->getQuery()
    ->getResult();

    $classe = new Classe();
    $form = $this->createForm(ClasseTypeForm::class, $classe, [
    'user' => $this->getUser(),
]);
$form->handleRequest($request);
if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($classe);
        $em->flush();
        return $this->redirectToRoute('app_classes');
    }

        $classes = $this->classeRepository->findByUserLycee($this->getUser());
        // trouver le nombre des eleves par classe
        $nbe=[];
        foreach ($classes as $classe) {
            $nbe[$classe->getId()] =[
                'nb' => $eleveRepository->count(['classe' => $classe]),
            ];
            
        }
        // $nbeleves = 0;
            // $eleves = $eleveRepository->findBy(['classe' => $classe]);
            // $nbeleves= count($eleves); 
        $rows = [];
        foreach ($classes as $classe1) { 
            $rows[$classe1->getId()] = [
                'percent' => $classeRepository->computePercent($classe1, $em),
            ];
        }
    return $this->render('classes/index.html.twig', [
        'form' => $form->createView(),
        'classes' => $classes,
        'actions' => $cntrl,
        'rows' => $rows,
        'nbelves' => $nbe,

    ]);
}

#[Route('/classe/delete/{id}', name: 'classe_delete')]
public function delete(Classe $classe, EntityManagerInterface $em): Response
{
    $em->remove($classe);
    $em->flush();
    return $this->redirectToRoute('app_classes');
}

#[Route('/classe/show/{id}', name: 'classe_rapport')]
public function show(Classe $classe,EleveRepository $eleveRepository, EntityManagerInterface $em,int $id): Response
{
    $class = $em->getRepository(Classe::class)->find($id);
    $eleves = $eleveRepository->findBy(['classe' => $classe]);
    $user = $this->getUser();

    if (!$class) {
    $this->addFlash('خطا', 'لا يمكن العثور على القسم');
    return $this->redirectToRoute('app_classes'); 
    }
    if ($class->getLycee()->getEtablissement()->getId() !== $user->getEtablissement()->getId() && !$this->isGranted('ROLE_ADMIN')) {
        $this->addFlash('تحذير', 'لا يمكنك عرض هذا القسم');
          return $this->redirectToRoute('app_classes'); // ou toute autre page de retour
    }

    return $this->render('rapports/classes/liste_eleve.html.twig', [
        'classe' => $classe,
        'eleves' => $eleves,
    ]);
}

#[Route('/listeno2/classe/{id}', name: 'listeno2_classe')]
    public function rapportClasse(
        int $id,
        EleveRepository $eleveRepository,
        EpreuveRepository $epreuveRepository,
        OperationBacRepository $operationBacRepository,
        EntityManagerInterface $em,
    ): Response {
        $class = $em->getRepository(Classe::class)->find($id);
        // dd($class);
        $eleves = $eleveRepository->findBy(['classe' => $id]);
        $user = $this->getUser();
    $eleveData = [];
    
    if (!$class) {
    throw $this->createNotFoundException("Classe non trouvée.");
    $this->addFlash('خطا', 'لا يمكن العثور على القسم');
    return $this->redirectToRoute('app_classes'); 
    }
    if ($class->getLycee()->getEtablissement()->getId() !== $user->getEtablissement()->getId() && !$this->isGranted('ROLE_ADMIN')) {
        $this->addFlash('تحذير', 'لا يمكنك عرض هذا القسم');
          return $this->redirectToRoute('app_classes'); // ou toute autre page de retour
    }
    // Identifiants des épreuves par type
    $epreuvesTypes = [
        'course' => [6, 7, 13],
        'saut' => [8, 9, 10],
        'poids' => [11],
        'gym' => [12],
    ];
    foreach ($eleves as $eleve) {
        $operations = $operationBacRepository->findBy(['eleve' => $eleve]);
        $dispenses = $em->getRepository(Dispense::class)->findOneBy(['eleve' => $eleve->getCin()]);
        $dispen = $dispenses ? $dispenses->getState() : "present";
        $notes = [];
        $total = 0;
        $count = 0;

        foreach ($operations as $op) {
            $epreuveId = $op->getEpreuve()->getId();
            $note = $op->getNote();
            $coef = $op->getResultat();
            $notes[$epreuveId] = [
                'note' => $note,
                'coef' => $coef,
            ];

            $total += floatval($note);
            $count++;
        }
        $moyenne = $count > 0 ? round($total / $count, 2) : null;
        $eleveData[] = [
            'nomPrenom' => $eleve->getNomPrenom(),
            'matricule' => $eleve->getCin(),
            'notes' => $notes,
            'total' => round($total, 2),
            'moyenne' => $moyenne,
            'dispen' => $dispen,
            'sexe' => $eleve->getSexe(),
        ];
    }

    return $this->render('rapports/classes/listeno2.html.twig', [
        'eleves' => $eleveData,
        'classe' => $class,
        'lycee'=> $class->getLycee(),

    ]);
    }

    
#[Route('/listeno2vide/classe/{id}', name: 'listeno2vide_classe')]
    public function rapportClasseVide(
        int $id,
        EleveRepository $eleveRepository,
        EpreuveRepository $epreuveRepository,
        OperationBacRepository $operationBacRepository,
        EntityManagerInterface $em,
    ): Response {
        $class = $em->getRepository(Classe::class)->find($id);
        $eleves = $eleveRepository->findBy(['classe' => $id]);
        $user = $this->getUser();
    $eleveData = [];
    if (!$class) {
    $this->addFlash('خطا', 'لا يمكن العثور على القسم');
    return $this->redirectToRoute('app_classes'); 
    }
    if ($class->getLycee()->getEtablissement()->getId() !== $user->getEtablissement()->getId() && !$this->isGranted('ROLE_ADMIN')) {
        $this->addFlash('تحذير', 'لا يمكنك عرض هذا القسم');
          return $this->redirectToRoute('app_classes'); // ou toute autre page de retour
    }

    // Identifiants des épreuves par type
    $epreuvesTypes = [
        'course' => [6, 7, 13],
        'saut' => [8, 9, 10],
        'poids' => [11],
        'gym' => [12],
    ];

    foreach ($eleves as $eleve) {
        $operations = $operationBacRepository->findBy(['eleve' => $eleve]);

        $notes = [];
        $total = 0;
        $count = 0;

        foreach ($operations as $op) {
            $epreuveId = $op->getEpreuve()->getId();
            $note = $op->getNote();
            $coef = $op->getResultat();
            $notes[$epreuveId] = [
                'note' => $note,
                'coef' => $coef
            ];

            $total += floatval($note);
            $count++;
        }

        $moyenne = $count > 0 ? round($total / $count, 2) : null;

        $eleveData[] = [
            'nomPrenom' => $eleve->getNomPrenom(),
            'matricule' => $eleve->getCin(),
            'notes' => $notes,
            'total' => round($total, 2),
            'moyenne' => $moyenne
        ];
    }

    return $this->render('rapports/classes/listeno2vide.html.twig', [
        'eleves' => $eleveData,
        'classe' => $class,
        'lycee'=> $class->getLycee(),

    ]);
    }

    #[Route('/classe/{id}/epreuves', name: 'app_classe_epreuves')]
public function afficherEpreuvesParClasse(
    int $id, 
    EntityManagerInterface $em
): Response {
    $classe = $em->getRepository(Classe::class)->find($id);
    $user = $this->getUser();
    if (!$classe) {
    $this->addFlash('خطا', 'لا يمكن العثور على القسم');
    return $this->redirectToRoute('app_classes'); 
    }
    if ($classe->getLycee()->getEtablissement()->getId() !== $user->getEtablissement()->getId() && !$this->isGranted('ROLE_ADMIN')) {
        $this->addFlash('تحذير', 'لا يمكنك إضهار القائمة عدد واحد لهذا القسم');
          return $this->redirectToRoute('app_classes'); // ou toute autre page de retour
    }

    $eleves = $em->getRepository(Eleve::class)->findBy(['classe' => $classe]);
   $order = [11, 9, 8, 10, 13, 7, 6]; // ordre d'affichage souhaité

$epreuves = $em->getRepository(Epreuve::class)->findBy([
    'id' => $order
]);

// Tri des objets selon l'ordre spécifié
usort($epreuves, function ($a, $b) use ($order) {
    return array_search($a->getId(), $order) <=> array_search($b->getId(), $order);
});
    // Créer une map des épreuves cochées
    $epreuveBac = $em->getRepository(EpreuveBac::class)->findAll();
    $epreuveMap = [];

    foreach ($epreuveBac as $relation) {
        $epreuveMap[$relation->getEleve()->getCin()][$relation->getEpreuve()->getId()] = true;
    }

    return $this->render('rapports/classes/listeno1.html.twig', [
        'classe' => $classe,
        'eleves' => $eleves,
        'epreuves' => $epreuves,
        'epreuveMap' => $epreuveMap,
    ]);
}

#[Route('/classe/{classe}/fiche-epreuve', name: 'app_fiche_epreuve')]
public function ficheEpreuve(
    Classe $classe,
    EleveRepository $eleveRepository,
    Request $request,
    EntityManagerInterface $em
): Response {
    $type = $request->query->getString('type'); // type est un entier
 $sex=$type[-1];
 if(strlen($type)==2){
    $epv=$type[0];
 }else{
    $epv=$type[0].$type[1];
 }
 $epreuve=$type;

    $eleves = $em->createQuery(
    'SELECT e
        FROM App\Entity\Eleve e
        JOIN App\Entity\EpreuveBac eb WITH eb.eleve = e
        WHERE e.classe = :classe
        AND e.sexe = :type
        AND eb.epreuve=:eprv
        ORDER BY e.nomPrenom ASC'
    )
    ->setParameters([
        'classe' => $classe,
        'type' => $sex,
        'eprv' => $epv
    ])
    ->getResult();

   $types = [
    '6h' => 'عدو ذكور',
    '7h' => 'عدو ذكور',
    '13h' => 'عدو ذكور',
    '6f' => 'عدو اناث',
    '7f' => 'عدو اناث',
    '13f' => 'عدو اناث',
    '8h' => 'وثب ذكور',
    '9h' => 'وثب ذكور',
    '10h' => 'وثب ذكور',
    '8f' => 'وثب اناث',
    '9f' => 'وثب اناث',
    '10f' => 'وثب اناث',
    '11h' => 'رمي ذكور',
    '11f' => 'رمي اناث',
    '12h' => 'جمباز ذكور',
    '12f' => 'جمباز اناث',
];

$type = $types[$type] ?? 'لا يوجد';

    return $this->render('rapports/classes/byepreuve.html.twig', [
        'classe' => $classe,
        'eleves' => $eleves,
        'type' => $type,
        'epreuve' => $epreuve
    ]);
}

#[Route('/statistique/genre', name: 'classe_genre_stats')]
public function genreStats(EntityManagerInterface $em): Response
{
    $repo = $em->getRepository(Classe::class);
    $eleves = $em->getRepository(Eleve::class)->findAll();

    // Regrouper les élèves par classe
    $data = [];

    foreach ($eleves as $eleve) {
        $classe = $eleve->getClasse();
        if (!$classe) continue;

        $nomClasse = $classe->getNomClasse();
        if (!isset($data[$nomClasse])) {
            $data[$nomClasse] = ['garcon' => 0, 'fille' => 0];
        }

        if (strtolower($eleve->getSexe()) === 'm') {
            $data[$nomClasse]['garcon']++;
        } else {
            $data[$nomClasse]['fille']++;
        }
    }

    return $this->render('home/genre.html.twig', [
        'data' => $data,
    ]);
}
#[Route('/convocation/classe/{id}', name: 'convocation_par_classe')]
public function convocationParClasse(Classe $classe, EntityManagerInterface $em): Response
{
    $eleves = $em->getRepository(Eleve::class)->findBy(['classe' => $classe]);

    if (count($eleves) === 0) {
        $this->addFlash('warning', 'Aucun élève trouvé dans cette classe.');
        return $this->redirectToRoute('app_classes');
    }

    $lycee = $classe->getLycee();
    $centre = $lycee->getCentre();
    $descrp = $centre ? $centre->getDescription() : '';

    return $this->render('classes/liste_par_classe.html.twig', [
        'eleves' => $eleves,
        'classe' => $classe->getNomClasse() . ' ' . $classe->getNum(),
        'section' => $classe->getNomClasse(),
        'school' => $lycee->getNom(),
        'delegation' => $lycee->getNom(), // à adapter si nécessaire
        'center' => $centre ? $centre->getCentre().' ('.$descrp.')' : 'غير محدد',
        'date' => $lycee->getDateEpreuve() ? $lycee->getDateEpreuve()->format('d/m/Y') : '',
        'time' => $lycee->getHeureEpreuve() ? $lycee->getHeureEpreuve()->format('H:i') : '',
        'year' => date('Y'),
        'type' => $lycee->getType(),
    ]);
}


    
}