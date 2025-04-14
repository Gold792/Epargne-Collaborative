<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MescontisationsController extends AbstractController
{
    #[Route('/mescontisations', name: 'app_mescontisations')]
    public function index(): Response
    {
        return $this->render('mescontisations/index.html.twig', [
            'controller_name' => 'MescontisationsController',
        ]);
    }
}
