<?php

namespace App\Controller;

use App\Entity\Cotisation;
use App\Entity\Echeance;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class EcheanceController extends AbstractController
{
    #[Route('/echeance', name: 'app_echeance')]
    public function index(): Response
    {
        return $this->render('echeance/index.html.twig', [
            'controller_name' => 'EcheanceController',
        ]);
    }

    #[Route('/echeance/{id}', name: 'app_echeance_show')]
    public function show($id): Response
    {
        $em = $this->getDoctrine()->getManager();

        // Récupère la cotisation
        $cotisation = $em->getRepository(Cotisation::class)->find($id);

        if (!$cotisation) {
            throw $this->createNotFoundException('Cotisation non trouvée');
        }

        // Récupère les échéances liées à cette cotisation
        $echeances = $em->getRepository(Echeance::class)->findBy(['cotisation' => $cotisation]);

        // Récupère les membres participants à cette cotisation
        $membres = $cotisation->getMembres(); // Doit exister dans ta relation (ManyToMany ou autre)

        // Récupère tous les utilisateurs disponibles pour ajout
        $utilisateursDisponibles = $em->getRepository(User::class)->findAll(); // Tu peux filtrer selon besoin

        return $this->render('echeance/index.html.twig', [
            'cotisation' => $cotisation,
            'echeances' => $echeances,
            'membres' => $membres,
            'utilisateursDisponibles' => $utilisateursDisponibles,
        ]);
    }
}
