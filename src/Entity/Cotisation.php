<?php

namespace App\Entity;

use App\Repository\CotisationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: CotisationRepository::class)]
class Cotisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le titre de la cotisation est obligatoire")]
    private ?string $titre = null;

    #[ORM\Column(length: 20)]
    #[Assert\Choice(
        choices: [self::TYPE_PERIODIQUE, self::TYPE_SOUSCRIPTION],
        message: "Le type de cotisation doit être valide"
    )]
    private ?string $typeCotisation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero(message: "Le montant objectif doit être positif ou égal à zéro")]
    private ?float $montantObjectif = null;

    #[ORM\Column]
    #[Assert\Positive(message: "Le montant minimum doit être positif")]
    private ?float $montantMinimum = null;

    #[ORM\Column(nullable: true)]
    #[Assert\PositiveOrZero(message: "Le montant par échéance doit être positif ou égal à zéro")]
    private ?float $montantParEcheance = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\GreaterThan(
        propertyPath: "dateDebut",
        message: "La date de fin doit être postérieure à la date de début."
    )]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Assert\Choice(
        choices: [self::PERIODICITE_JOURNALIER, self::PERIODICITE_HEBDOMADAIRE, self::PERIODICITE_MENSUEL],
        message: "La périodicité doit être valide"
    )]
    private ?string $periodicite = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Range(
        min: 1,
        max: 12,
        notInRangeMessage: "La fréquence doit être comprise entre 1 et 12"
    )]
    private ?int $frequencePeriode = null;

    // Constantes pour les types de cotisation
    public const TYPE_PERIODIQUE = 'periodique';
    public const TYPE_SOUSCRIPTION = 'souscription';

    // Constantes pour les périodicités
    public const PERIODICITE_JOURNALIER = 'journalier';
    public const PERIODICITE_HEBDOMADAIRE = 'hebdomadaire';
    public const PERIODICITE_MENSUEL = 'mensuel';

    public function __construct()
    {
        $this->dateDebut = new \DateTime();
    }

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

    public function getTypeCotisation(): ?string
    {
        return $this->typeCotisation;
    }

    public function setTypeCotisation(string $typeCotisation): static
    {
        $this->typeCotisation = $typeCotisation;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getMontantObjectif(): ?float
    {
        return $this->montantObjectif;
    }

    public function setMontantObjectif(float $montantObjectif): static
    {
        $this->montantObjectif = $montantObjectif;
        return $this;
    }

    public function getMontantMinimum(): ?float
    {
        return $this->montantMinimum;
    }

    public function setMontantMinimum(float $montantMinimum): static
    {
        $this->montantMinimum = $montantMinimum;
        return $this;
    }

    public function getMontantParEcheance(): ?float
    {
        return $this->montantParEcheance;
    }

    public function setMontantParEcheance(?float $montantParEcheance): static
    {
        $this->montantParEcheance = $montantParEcheance;
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

    public function setDateFin(?\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;
        return $this;
    }

    public function getPeriodicite(): ?string
    {
        return $this->periodicite;
    }

    public function setPeriodicite(?string $periodicite): static
    {
        $this->periodicite = $periodicite;
        return $this;
    }

    public function getFrequencePeriode(): ?int
    {
        return $this->frequencePeriode;
    }

    public function setFrequencePeriode(?int $frequencePeriode): static
    {
        $this->frequencePeriode = $frequencePeriode;
        return $this;
    }

    // Méthode pour obtenir les types de cotisation disponibles
    public static function getTypesCotisation(): array
    {
        return [
            'Cotisation régulière (montant fixe)' => self::TYPE_PERIODIQUE,
            'Contribution libre (montant minimum)' => self::TYPE_SOUSCRIPTION,
        ];
    }

    // Méthode pour obtenir les périodicités disponibles
    public static function getPeriodicites(): array
    {
        return [
            'Journalier' => self::PERIODICITE_JOURNALIER,
            'Hebdomadaire' => self::PERIODICITE_HEBDOMADAIRE,
            'Mensuel' => self::PERIODICITE_MENSUEL,
        ];
    }

    // Méthode pour obtenir les fréquences de période disponibles
    public static function getFrequencesPeriode(): array
    {
        $frequences = [];
        for ($i = 1; $i <= 12; $i++) {
            $frequences[$i] = $i;
        }
        return $frequences;
    }

    // Méthode pour les validations personnalisées
    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        if ($this->typeCotisation === self::TYPE_PERIODIQUE) {
            if ($this->montantParEcheance === null) {
                $context->buildViolation("Le montant par échéance est requis pour une cotisation périodique.")
                    ->atPath("montantParEcheance")
                    ->addViolation();
            }
            if ($this->periodicite === null) {
                $context->buildViolation("La périodicité est requise pour une cotisation périodique.")
                    ->atPath("periodicite")
                    ->addViolation();
            }
        }
    }

    // Pour faciliter l'affichage dans les formulaires
    public function __toString(): string
    {
        return $this->titre ?? 'Cotisation';
    }
}
