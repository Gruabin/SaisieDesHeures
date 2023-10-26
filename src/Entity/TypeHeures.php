<?php

namespace App\Entity;

use App\Repository\TypeHeuresRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeHeuresRepository::class)]
class TypeHeures
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomType = null;

    #[ORM\OneToMany(mappedBy: 'typeHeures', targetEntity: DetailHeures::class)]
    private $detailHeures;

    public function __construct()
    {
        $this->detailHeures = new DetailHeures();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomType(): ?string
    {
        return $this->nomType;
    }

    public function setNomType(string $nomType): static
    {
        $this->nomType = $nomType;

        return $this;
    }

    public function getDetailHeures(): ?DetailHeures
    {
        return $this->detailHeures;
    }

    public function addDetailHeure(DetailHeures $detailHeure): static
    {
        if (!$this->detailHeures->contains($detailHeure)) {
            $this->detailHeures->add($detailHeure);
            $detailHeure->addTypeHeure($this);
        }

        return $this;
    }

    public function removeDetailHeure(DetailHeures $detailHeure): static
    {
        if ($this->detailHeures->removeElement($detailHeure)) {
            $detailHeure->removeTypeHeure($this);
        }

        return $this;
    }
}
