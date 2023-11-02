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
    private ?string $temps_main_oeuvre = null;

    #[ORM\ManyToOne(inversedBy: 'detailHeures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeHeures $type_heures = null;

    #[ORM\ManyToOne(inversedBy: 'detailHeures')]
    private ?Ordre $ordre = null;

    #[ORM\ManyToOne(inversedBy: 'detailHeures')]
    private ?Operation $operation = null;

    #[ORM\ManyToOne(inversedBy: 'detailHeures')]
    private ?Tache $tache = null;

    #[ORM\ManyToOne(inversedBy: 'detailHeures')]
    private ?Activite $activite = null;

    #[ORM\ManyToOne(inversedBy: 'detailHeures')]
    private ?CentreDeCharge $centre_de_charge = null;

    #[ORM\ManyToOne(inversedBy: 'detailHeures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Employe $employe = null;

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
        return $this->temps_main_oeuvre;
    }

    public function setTempsMainOeuvre(string $temps_main_oeuvre): static
    {
        $this->temps_main_oeuvre = $temps_main_oeuvre;

        return $this;
    }

    public function getTypeHeures(): ?TypeHeures
    {
        return $this->type_heures;
    }

    public function setTypeHeures(?TypeHeures $type_heures): static
    {
        $this->type_heures = $type_heures;

        return $this;
    }

    public function getOrdre(): ?Ordre
    {
        return $this->ordre;
    }

    public function setOrdre(?Ordre $ordre): static
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getOperation(): ?Operation
    {
        return $this->operation;
    }

    public function setOperation(?Operation $operation): static
    {
        $this->operation = $operation;

        return $this;
    }

    public function getTache(): ?Tache
    {
        return $this->tache;
    }

    public function setTache(?Tache $tache): static
    {
        $this->tache = $tache;

        return $this;
    }

    public function getActivite(): ?Activite
    {
        return $this->activite;
    }

    public function setActivite(?Activite $activite): static
    {
        $this->activite = $activite;

        return $this;
    }

    public function getCentreDeCharge(): ?CentreDeCharge
    {
        return $this->centre_de_charge;
    }

    public function setCentreDeCharge(?CentreDeCharge $centre_de_charge): static
    {
        $this->centre_de_charge = $centre_de_charge;

        return $this;
    }

    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(?Employe $employe): static
    {
        $this->employe = $employe;

        return $this;
    }
}
