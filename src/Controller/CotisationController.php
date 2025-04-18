<?php

namespace App\Controller;

use App\Entity\Cotisation;
use App\Form\CotisationType;
use App\Entity\Participer;
use App\Repository\CotisationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cotisation')]
class CotisationController extends AbstractController
{
    #[Route('/', name: 'app_cotisation_index', methods: ['GET'])]
    public function index(CotisationRepository $cotisationRepository, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = $this->getUser();
        
        // Option 1: Utiliser le repository avec une méthode personnalisée
        $mesCotisations = $cotisationRepository->findCotisationsForUser($utilisateur);
        
        // OU Option 2: Utiliser une requête DQL directement
        $query = $entityManager->createQuery(
            'SELECT c FROM App\Entity\Cotisation c
             JOIN App\Entity\Participer p WITH c.id = p.cotisation
             WHERE p.utilisateur = :utilisateur'
        )->setParameter('utilisateur', $utilisateur);
        
        $mesCotisations = $query->getResult();
        
        return $this->render('cotisation/index.html.twig', [
            'cotisations' => $mesCotisations,
        ]);
    }

    #[Route('/new', name: 'app_cotisation_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $cotisation = new Cotisation();
    $form = $this->createForm(CotisationType::class, $cotisation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Définir l'utilisateur courant comme créateur
        $utilisateur = $this->getUser();
        $cotisation->setCreateur($utilisateur);
        
        $entityManager->persist($cotisation);
        $entityManager->flush();
        
        // Créer l'entrée dans la table participer
        $participation = new Participer();
        $participation->setCotisation($cotisation);
        $participation->setUtilisateur($utilisateur);
        $participation->setDateEntree(new \DateTime());
        $participation->setProprietaire(true);
        
        $entityManager->persist($participation);
        $entityManager->flush();

        $this->addFlash('success', 'La cotisation a été créée avec succès.');
        return $this->redirectToRoute('app_cotisation_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('cotisation/new.html.twig', [
        'cotisation' => $cotisation,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_cotisation_show', methods: ['GET'])]
    public function show(Cotisation $cotisation): Response
    {
        return $this->render('cotisation/show.html.twig', [
            'cotisation' => $cotisation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cotisation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cotisation $cotisation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CotisationType::class, $cotisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'La cotisation a été modifiée avec succès.');
            return $this->redirectToRoute('app_cotisation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cotisation/edit.html.twig', [
            'cotisation' => $cotisation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cotisation_delete', methods: ['POST'])]
    public function delete(Request $request, Cotisation $cotisation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cotisation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cotisation);
            $entityManager->flush();
            $this->addFlash('success', 'La cotisation a été supprimée avec succès.');
        }

        return $this->redirectToRoute('app_cotisation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/fixture/add', name: 'app_cotisation_add_fixtures', methods: ['GET'])]
    public function addFixtures(EntityManagerInterface $entityManager): Response
    {
        // Ajout des cotisations prédéfinies
        $this->addPredefinedCotisations($entityManager);
        
        $this->addFlash('success', 'Les cotisations prédéfinies ont été ajoutées avec succès.');
        return $this->redirectToRoute('app_cotisation_index');
    }

    private function addPredefinedCotisations(EntityManagerInterface $entityManager): void
    {
        // Cotisation périodique
        $cotisation1 = new Cotisation();
        $cotisation1->setTitre('Cotisation annuelle standard');
        $cotisation1->setTypeCotisation(Cotisation::TYPE_PERIODIQUE);
        $cotisation1->setDescription('Cotisation régulière annuelle pour les membres de l\'association');
        $cotisation1->setMontantObjectif(10000.00);
        $cotisation1->setMontantMinimum(50.00);
        $cotisation1->setMontantParEcheance(50.00);
        $cotisation1->setDateDebut(new \DateTime());
        $cotisation1->setDateFin((new \DateTime())->modify('+1 year'));
        $cotisation1->setPeriodicite(Cotisation::PERIODICITE_MENSUEL);
        $cotisation1->setFrequencePeriode(12);
        
        $entityManager->persist($cotisation1);
        
        // Cotisation souscription
        $cotisation2 = new Cotisation();
        $cotisation2->setTitre('Dons pour le projet communautaire');
        $cotisation2->setTypeCotisation(Cotisation::TYPE_SOUSCRIPTION);
        $cotisation2->setDescription('Campagne de dons pour financer le nouveau projet communautaire');
        $cotisation2->setMontantObjectif(5000.00);
        $cotisation2->setMontantMinimum(10.00);
        $cotisation2->setDateDebut(new \DateTime());
        
        $entityManager->persist($cotisation2);
        
        // Cotisation périodique hebdomadaire
        $cotisation3 = new Cotisation();
        $cotisation3->setTitre('Contribution hebdomadaire');
        $cotisation3->setTypeCotisation(Cotisation::TYPE_PERIODIQUE);
        $cotisation3->setDescription('Contribution hebdomadaire pour les activités régulières');
        $cotisation3->setMontantObjectif(2000.00);
        $cotisation3->setMontantMinimum(15.00);
        $cotisation3->setMontantParEcheance(15.00);
        $cotisation3->setDateDebut(new \DateTime());
        $cotisation3->setDateFin((new \DateTime())->modify('+6 months'));
        $cotisation3->setPeriodicite(Cotisation::PERIODICITE_HEBDOMADAIRE);
        $cotisation3->setFrequencePeriode(1);
        
        $entityManager->persist($cotisation3);
        
        $entityManager->flush();
    }

    #[Route('/cotisation/modifier/{id}', name: 'modifier_cotisation', methods: ['POST'])]
    public function modifierCotisation(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $titre = $request->request->get('titre');

        // Récupérer la cotisation depuis la base de données
        $cotisation = $entityManager->getRepository(Cotisation::class)->find($id);

        if (!$cotisation) {
            throw $this->createNotFoundException('Cotisation non trouvée.');
        }

        // Mettre à jour le titre
        $cotisation->setTitre($titre);
        $entityManager->flush();

        // Rediriger vers la page de la cotisation
        return $this->redirectToRoute('app_cotisation_show', ['id' => $id]);
    }

    #[Route('/{id}/join', name: 'app_cotisation_join', methods: ['POST'])]
public function joinCotisation(Cotisation $cotisation, EntityManagerInterface $entityManager): Response
{
    $utilisateur = $this->getUser();
    
    // Vérifier si l'utilisateur est déjà membre de cette cotisation
    $participationExistante = $entityManager->getRepository(Participer::class)->findOneBy([
        'cotisation' => $cotisation,
        'utilisateur' => $utilisateur
    ]);
    
    if (!$participationExistante) {
        $participation = new Participer();
        $participation->setCotisation($cotisation);
        $participation->setUtilisateur($utilisateur);
        $participation->setDateEntree(new \DateTime());
        $participation->setProprietaire(false);
        
        $entityManager->persist($participation);
        $entityManager->flush();
        
        $this->addFlash('success', 'Vous avez rejoint cette cotisation avec succès.');
    } else {
        $this->addFlash('info', 'Vous êtes déjà membre de cette cotisation.');
    }
    
    return $this->redirectToRoute('app_cotisation_show', ['id' => $cotisation->getId()]);
}

#[Route('/search-member', name: 'app_search_member', methods: ['POST'])]
public function searchMember(Request $request, EntityManagerInterface $entityManager): Response
{
    $telephone = $request->request->get('phone');
    
    $utilisateur = $entityManager->getRepository(Utilisateur::class)->findOneBy(['telephone' => $telephone]);
    
    if ($utilisateur) {
        return $this->json([
            'found' => true,
            'user' => [
                'id' => $utilisateur->getId(),
                'nom' => $utilisateur->getNom(),
                'prenom' => $utilisateur->getPrenom(),
                'telephone' => $utilisateur->getTelephone(),
                'initials' => substr($utilisateur->getNom(), 0, 1) . substr($utilisateur->getPrenom(), 0, 1)
            ]
        ]);
    }
    
    return $this->json(['found' => false]);
}

#[Route('/{id}/add-member/{userId}', name: 'app_cotisation_add_member', methods: ['POST'])]
public function addMember(Cotisation $cotisation, int $userId, EntityManagerInterface $entityManager): Response
{
    $utilisateur = $entityManager->getRepository(Utilisateur::class)->find($userId);
    
    if (!$utilisateur) {
        $this->addFlash('error', 'Utilisateur non trouvé.');
        return $this->redirectToRoute('app_cotisation_show', ['id' => $cotisation->getId()]);
    }
    
    // Vérifier si l'utilisateur est déjà membre
    $participationExistante = $entityManager->getRepository(Participer::class)->findOneBy([
        'cotisation' => $cotisation,
        'utilisateur' => $utilisateur
    ]);
    
    if (!$participationExistante) {
        $participation = new Participer();
        $participation->setCotisation($cotisation);
        $participation->setUtilisateur($utilisateur);
        $participation->setDateEntree(new \DateTime());
        $participation->setProprietaire(false);
        
        $entityManager->persist($participation);
        $entityManager->flush();
        
        $this->addFlash('success', 'Membre ajouté avec succès.');
    } else {
        $this->addFlash('info', 'Cet utilisateur est déjà membre de cette cotisation.');
    }
    
    return $this->redirectToRoute('app_cotisation_show', ['id' => $cotisation->getId()]);
}
}
    
