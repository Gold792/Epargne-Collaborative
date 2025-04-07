<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PotiliqueController extends AbstractController
{
    #[Route('/potilique', name: 'app_potilique')]
    public function index(): Response
    {
        return $this->render('potilique/index.html.twig', [
            'controller_name' => 'PotiliqueController',
        ]);
    }
}
