<?php

namespace App\Controller;

use App\Entity\Cotisation;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreationCotisationController extends AbstractController
{
    #[Route('/creation/cotisation', name: 'app_creation_cotisation', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            // Récupérer les données du formulaire
            $joursPeriodicite = $request->request->get('joursPeriodicite');

            // Créer une nouvelle entité Cotisation
            $cotisation = new Cotisation();
            
            $cotisation->setTitre($request->request->get('titre'));
            $cotisation->setInitiateur($request->request->get('initiateur'));
            $cotisation->setMontantObjectif($request->request->getInt('montantObjectif'));
            $cotisation->setMontantEcheance($request->request->getInt('montantEcheance'));
            $cotisation->setDateDebut(new \DateTime($request->request->get('dateDebut')));
            
            $dateFin = $request->request->get('dateFin');
            if ($dateFin) {
                $cotisation->setDateFin(new \DateTime($dateFin));
            }

            $cotisation->setPeriodicite($request->request->get('periodicite'));
            $cotisation->setJoursPeriodicite($joursPeriodicite ?: null); // Accepter null si non défini
            $cotisation->setRappelAuto($request->request->has('rappelAuto'));
            $cotisation->setCotisationPublique($request->request->has('cotisationPublique'));
            $cotisation->setDescription($request->request->get('description'));

            // Ajout des membres
            $membresIds = explode(',', $request->request->get('membres'));
            foreach ($membresIds as $id) {
                $utilisateur = $em->getRepository(Utilisateur::class)->find($id);
                if ($utilisateur) {
                    $cotisation->addMembre($utilisateur);
                }
            }

            $em->persist($cotisation);
            $em->flush();

            return $this->redirectToRoute('app_utilisateur'); // ou une autre route selon ton besoin
        }

        // Affichez le formulaire
        return $this->render('creation_cotisation/index.html.twig');
    }
}
