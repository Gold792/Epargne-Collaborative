<?php
namespace App\Entity;

use App\Repository\ParticiperRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticiperRepository::class)]
class Participer
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $utilisateur;
    
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Cotisation::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $cotisation;
    
    #[ORM\Column(type: 'datetime')]
    private $dateEntree;
    
    #[ORM\Column(type: 'boolean')]
    private $proprietaire;
    
    // Getters & Setters...

    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    public function setUtilisateur($utilisateur): self
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    public function getCotisation()
    {
        return $this->cotisation;
    }

    public function setCotisation($cotisation): self
    {
        $this->cotisation = $cotisation;
        return $this;
    }

    public function getDateEntree()
    {
        return $this->dateEntree;
    }

    public function setDateEntree($dateEntree): self
    {
        $this->dateEntree = $dateEntree;
        return $this;
    }

    public function getProprietaire(): bool
    {
        return $this->proprietaire;
    }

    public function setProprietaire(bool $proprietaire): self
    {
        $this->proprietaire = $proprietaire;
        return $this;
    }
}