<?php
// src/Controller/QrCodeController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\PngRenderer;
class QrCodeController extends AbstractController
{
    #[Route('/qrcode/{data}', name: 'generate_qrcode')]
    public function generate(string $data): Response
    {
        $datas = urlencode($data);
$qrUrl = "https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=$datas&chld=L|1";

return $this->redirect($qrUrl);

    }
}
