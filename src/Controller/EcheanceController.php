<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EcheanceController extends AbstractController
{
    #[Route('/echeance', name: 'app_echeance')]
    public function index(): Response
    {
        return $this->render('echeance/index.html.twig', [
            'controller_name' => 'EcheanceController',
        ]);
    }
}
