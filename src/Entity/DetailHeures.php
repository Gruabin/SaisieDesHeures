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
    private ?typeHeures $type_heures = null;

    #[ORM\ManyToOne(inversedBy: 'detailHeures')]
    private ?ordre $ordre = null;

    #[ORM\ManyToOne(inversedBy: 'detailHeures')]
    private ?operation $operation = null;

    #[ORM\ManyToOne(inversedBy: 'detailHeures')]
    private ?tache $tache = null;

    #[ORM\ManyToOne(inversedBy: 'detailHeures')]
    private ?activite $activite = null;

    #[ORM\ManyToOne(inversedBy: 'detailHeures')]
    private ?centreDeCharge $centre_de_charge = null;

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

    public function getTypeHeures(): ?typeHeures
    {
        return $this->type_heures;
    }

    public function setTypeHeures(?typeHeures $type_heures): static
    {
        $this->type_heures = $type_heures;

        return $this;
    }

    public function getOrdre(): ?ordre
    {
        return $this->ordre;
    }

    public function setOrdre(?ordre $ordre): static
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getOperation(): ?operation
    {
        return $this->operation;
    }

    public function setOperation(?operation $operation): static
    {
        $this->operation = $operation;

        return $this;
    }

    public function getTache(): ?tache
    {
        return $this->tache;
    }

    public function setTache(?tache $tache): static
    {
        $this->tache = $tache;

        return $this;
    }

    public function getActivite(): ?activite
    {
        return $this->activite;
    }

    public function setActivite(?activite $activite): static
    {
        $this->activite = $activite;

        return $this;
    }

    public function getCentreDeCharge(): ?centreDeCharge
    {
        return $this->centre_de_charge;
    }

    public function setCentreDeCharge(?centreDeCharge $centre_de_charge): static
    {
        $this->centre_de_charge = $centre_de_charge;

        return $this;
    }
}
