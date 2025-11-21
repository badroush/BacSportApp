<?php
namespace App\Controller;

use App\Repository\EleveRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CinGeneratorController extends AbstractController
{
    private EleveRepository $eleveRepository;

    public function __construct(EleveRepository $eleveRepository)
    {
        $this->eleveRepository = $eleveRepository;
    }

    #[Route('/api/generate-cin', name: 'api_generate_cin', methods: ['GET'])]
    public function generateCin(Request $request): JsonResponse
    {
        $matricule = $request->query->get('matricule');

        if (!$matricule || strlen($matricule) < 4) {
            return new JsonResponse(['error' => 'Matricule invalide'], 400);
        }

        $baseCin = substr($matricule, -4);
        $cinCandidate = $baseCin;

        // Si le CIN est déjà utilisé, on génère un nombre aléatoire à 4 chiffres
        while ($this->eleveRepository->findOneBy(['cin' => $cinCandidate])) {
            $cinCandidate = strval(random_int(1000, 9999));
        }

        return new JsonResponse(['cin' => $cinCandidate]);
    }
}
