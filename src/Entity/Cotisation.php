<?php

namespace App\Entity;

use App\Repository\CotisationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CotisationRepository::class)]
class Cotisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $initiateur = null;

    #[ORM\Column(length: 255)]
    private ?string $montantObjectif = null;

    #[ORM\Column(length: 255)]
    private ?string $montantEcheance = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(length: 255)]
    private ?string $periodicite = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $joursPeriodicite = null;

    #[ORM\Column]
    private ?bool $rappelAuto = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: 'boolean')]
    private bool $cotisationPublique = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getInitiateur(): ?string
    {
        return $this->initiateur;
    }

    public function setInitiateur(string $initiateur): static
    {
        $this->initiateur = $initiateur;

        return $this;
    }

    public function getMontantObjectif(): ?string
    {
        return $this->montantObjectif;
    }

    public function setMontantObjectif(string $montantObjectif): static
    {
        $this->montantObjectif = $montantObjectif;

        return $this;
    }

    public function getMontantEcheance(): ?string
    {
        return $this->montantEcheance;
    }

    public function setMontantEcheance(string $montantEcheance): static
    {
        $this->montantEcheance = $montantEcheance;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getPeriodicite(): ?string
    {
        return $this->periodicite;
    }

    public function setPeriodicite(string $periodicite): static
    {
        $this->periodicite = $periodicite;

        return $this;
    }

    public function getJoursPeriodicite(): ?string
    {
        return $this->joursPeriodicite;
    }

    public function setJoursPeriodicite(?string $joursPeriodicite): static
    {
        $this->joursPeriodicite = $joursPeriodicite;

        return $this;
    }

    public function isRappelAuto(): ?bool
    {
        return $this->rappelAuto;
    }

    public function setRappelAuto(bool $rappelAuto): static
    {
        $this->rappelAuto = $rappelAuto;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isCotisationPublique(): bool
    {
        return $this->cotisationPublique;
    }

    public function setCotisationPublique(bool $cotisationPublique): self
    {
        $this->cotisationPublique = $cotisationPublique;
        return $this;
    }
}
