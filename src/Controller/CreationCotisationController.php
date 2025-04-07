<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CreationCotisationController extends AbstractController
{
    #[Route('/creation/cotisation', name: 'app_creation_cotisation')]
    public function index(): Response
    {
        return $this->render('creation_cotisation/index.html.twig', [
            'controller_name' => 'CreationCotisationController',
        ]);
    }
}
