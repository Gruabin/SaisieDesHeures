<?php

namespace App\Entity;

use App\Repository\DetailHeuresRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DetailHeuresRepository::class)]
class DetailHeures
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $tempsMainOeuvre = null;

    #[ORM\ManyToOne(targetEntity: TypeHeures::class, inversedBy: 'detailHeures')]
    #[ORM\JoinColumn(nullable: false)]
    private $typeHeures;

    #[ORM\ManyToOne(targetEntity: Ordre::class, inversedBy: 'detailHeures')]
    private $ordre;

    #[ORM\ManyToOne(targetEntity: Operation::class, inversedBy: 'detailHeures')]
    private $operation;

    #[ORM\ManyToOne(targetEntity: Tache::class, inversedBy: 'detailHeures')]
    private $tache;

    #[ORM\ManyToOne(targetEntity: Activite::class, inversedBy: 'detailHeures')]
    private $activite;

    #[ORM\ManyToOne(targetEntity: CentreDeCharge::class, inversedBy: 'detailHeures')]
    private $centreDeCharge;

    public function __construct()
    {
        $this->typeHeures = new TypeHeures();
        $this->ordre = new Ordre();
        $this->operation = new Operation();
        $this->tache = new Tache();
        $this->activite = new Activite();
        $this->centreDeCharge = new CentreDeCharge();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTempsMainOeuvre(): ?string
    {
        return $this->tempsMainOeuvre;
    }

    public function setTempsMainOeuvre(string $tempsMainOeuvre): static
    {
        $this->tempsMainOeuvre = $tempsMainOeuvre;

        return $this;
    }

    public function getTypeHeures(): ?TypeHeures
    {
        return $this->typeHeures;
    }

    public function addTypeHeure(TypeHeures $typeHeure): static
    {
        if (!$this->typeHeures->contains($typeHeure)) {
            $this->typeHeures->add($typeHeure);
            $typeHeure->addDetailHeure($this);
        }

        return $this;
    }

    public function removeTypeHeure(TypeHeures $typeHeure): static
    {
        if ($this->typeHeures->removeElement($typeHeure)) {
            $typeHeure->removeDetailHeure($this);
        }

        return $this;
    }

    public function getOrdre(): ?Ordre
    {
        return $this->ordre;
    }

    public function addOrdre(Ordre $ordre): static
    {
        if (!$this->ordre->contains($ordre)) {
            $this->ordre->add($ordre);
            $ordre->addDetailHeure($this);
        }

        return $this;
    }

    public function removeOrdre(Ordre $ordre): static
    {
        if ($this->ordre->removeElement($ordre)) {
            $ordre->removeDetailHeure($this);
        }

        return $this;
    }

    public function getOperation(): ?Operation
    {
        return $this->operation;
    }

    public function addOperation(Operation $operation): static
    {
        if (!$this->operation->contains($operation)) {
            $this->operation->add($operation);
            $operation->addDetailHeure($this);
        }

        return $this;
    }

    public function removeOperation(Operation $operation): static
    {
        if ($this->operation->removeElement($operation)) {
            $operation->removeDetailHeure($this);
        }

        return $this;
    }

    public function getTache(): ?Tache
    {
        return $this->tache;
    }

    public function addTache(Tache $tache): static
    {
        if (!$this->tache->contains($tache)) {
            $this->tache->add($tache);
            $tache->addDetailHeure($this);
        }

        return $this;
    }

    public function removeTache(Tache $tache): static
    {
        if ($this->tache->removeElement($tache)) {
            $tache->removeDetailHeure($this);
        }

        return $this;
    }

    public function getActivite(): ?Activite
    {
        return $this->activite;
    }

    public function addActivite(Activite $activite): static
    {
        if (!$this->activite->contains($activite)) {
            $this->activite->add($activite);
            $activite->addDetailHeure($this);
        }

        return $this;
    }

    public function removeActivite(Activite $activite): static
    {
        if ($this->activite->removeElement($activite)) {
            $activite->removeDetailHeure($this);
        }

        return $this;
    }

    public function getCentreDeCharge(): ?CentreDeCharge
    {
        return $this->centreDeCharge;
    }

    public function addCentreDeCharge(CentreDeCharge $centreDeCharge): static
    {
        if (!$this->centreDeCharge->contains($centreDeCharge)) {
            $this->centreDeCharge->add($centreDeCharge);
            $centreDeCharge->addDetailHeure($this);
        }

        return $this;
    }

    public function removeCentreDeCharge(CentreDeCharge $centreDeCharge): static
    {
        if ($this->centreDeCharge->removeElement($centreDeCharge)) {
            $centreDeCharge->removeDetailHeure($this);
        }

        return $this;
    }
}
