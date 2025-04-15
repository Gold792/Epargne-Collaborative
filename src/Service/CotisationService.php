<?php

namespace App\Service;

use App\Entity\Cotisation;
use App\Repository\CotisationRepository;
use Doctrine\ORM\EntityManagerInterface;

class CotisationService
{
    private $cotisationRepository;
    private $entityManager;
    
    public function __construct(CotisationRepository $cotisationRepository, EntityManagerInterface $entityManager)
    {
        $this->cotisationRepository = $cotisationRepository;
        $this->entityManager = $entityManager;
    }
    
    /**
     * Récupérer toutes les cotisations actives (dont la date de fin n'est pas dépassée)
     */
    public function getCotisationsActives(): array
    {
        $cotisations = $this->cotisationRepository->findAll();
        $cotisationsActives = [];
        
        $now = new \DateTime();
        
        foreach ($cotisations as $cotisation) {
            // Si la date de fin est null ou supérieure à maintenant, la cotisation est active
            if ($cotisation->getDateFin() === null || $cotisation->getDateFin() > $now) {
                $cotisationsActives[] = $cotisation;
            }
        }
        
        return $cotisationsActives;
    }
    
    /**
     * Retourne les cotisations par type
     */
    public function getCotisationsByType(string $type): array
    {
        return $this->cotisationRepository->findBy(['typeCotisation' => $type]);
    }
    
    /**
     * Calcule le montant total attendu pour une cotisation périodique
     */
    public function calculerMontantTotalPeriodique(Cotisation $cotisation): ?float
    {
        // Vérifier que c'est bien une cotisation périodique
        if ($cotisation->getTypeCotisation() !== Cotisation::TYPE_PERIODIQUE) {
            return null;
        }
        
        // Si pas de date de fin, on ne peut pas calculer le montant total
        if ($cotisation->getDateFin() === null) {
            return null;
        }
        
        $dateDebut = $cotisation->getDateDebut();
        $dateFin = $cotisation->getDateFin();
        $montantParEcheance = $cotisation->getMontantParEcheance();
        $periodicite = $cotisation->getPeriodicite();
        $frequence = $cotisation->getFrequencePeriode();
        
        if (!$montantParEcheance || !$periodicite || !$frequence) {
            return null;
        }
        
        // Calcul du nombre de périodes entre la date de début et la date de fin
        $interval = $dateDebut->diff($dateFin);
        $nbJours = $interval->days;
        
        $nbPeriodes = 0;
        
        switch ($periodicite) {
            case Cotisation::PERIODICITE_JOURNALIER:
                $nbPeriodes = ceil($nbJours / $frequence);
                break;
            case Cotisation::PERIODICITE_HEBDOMADAIRE:
                $nbPeriodes = ceil($nbJours / (7 * $frequence));
                break;
            case Cotisation::PERIODICITE_MENSUEL:
                // Nombre de mois entre les deux dates
                $nbMois = ($interval->y * 12) + $interval->m;
                if ($interval->d > 0) {
                    $nbMois++;
                }
                $nbPeriodes = ceil($nbMois / $frequence);
                break;
        }
        
        return $nbPeriodes * $montantParEcheance;
    }
    
    /**
     * Vérifie si une cotisation est valide (dates, montants, etc.)
     */
    public function validerCotisation(Cotisation $cotisation): array
    {
        $erreurs = [];
        
        // Vérifier les dates
        if ($cotisation->getDateFin() && $cotisation->getDateFin() < $cotisation->getDateDebut()) {
            $erreurs[] = "La date de fin ne peut pas être antérieure à la date de début";
        }
        
        // Vérifier les montants
        if ($cotisation->getMontantMinimum() <= 0) {
            $erreurs[] = "Le montant minimum doit être supérieur à 0";
        }
        
        if ($cotisation->getMontantMinimum() > $cotisation->getMontantObjectif()) {
            $erreurs[] = "Le montant minimum ne peut pas être supérieur au montant objectif";
        }
        
        // Vérifications spécifiques au type périodique
        if ($cotisation->getTypeCotisation() === Cotisation::TYPE_PERIODIQUE) {
            if ($cotisation->getMontantParEcheance() === null) {
                $erreurs[] = "Le montant par échéance est obligatoire pour une cotisation périodique";
            } elseif ($cotisation->getMontantParEcheance() < $cotisation->getMontantMinimum()) {
                $erreurs[] = "Le montant par échéance ne peut pas être inférieur au montant minimum";
            }
            
            if ($cotisation->getPeriodicite() === null) {
                $erreurs[] = "La périodicité est obligatoire pour une cotisation périodique";
            }
            
            if ($cotisation->getFrequencePeriode() === null) {
                $erreurs[] = "La fréquence de période est obligatoire pour une cotisation périodique";
            } elseif ($cotisation->getFrequencePeriode() < 1 || $cotisation->getFrequencePeriode() > 12) {
                $erreurs[] = "La fréquence de période doit être comprise entre 1 et 12";
            }
        }
        
        return $erreurs;
    }
}