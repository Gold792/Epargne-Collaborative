<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GroupeCotisationController extends AbstractController
{
    #[Route('/groupe/cotisation', name: 'app_groupe_cotisation')]
    public function index(): Response
    {
        return $this->render('groupe_cotisation/index.html.twig', [
            'controller_name' => 'GroupeCotisationController',
        ]);
    }
}
