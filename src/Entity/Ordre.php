<?php

namespace App\Entity;

use App\Repository\OrdreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdreRepository::class)]
class Ordre
{
    #[ORM\Id]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $descriptionOrdre = null;

    #[ORM\OneToMany(mappedBy: 'ordre', targetEntity: DetailHeures::class)]
    private $detailHeures;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getDescriptionOrdre(): ?string
    {
        return $this->descriptionOrdre;
    }

    public function setDescriptionOrdre(string $descriptionOrdre): static
    {
        $this->descriptionOrdre = $descriptionOrdre;

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
            $detailHeure->setOrdre($this);
        }

        return $this;
    }

    public function removeDetailHeure(DetailHeures $detailHeure): static
    {
        if ($this->detailHeures->removeElement($detailHeure)) {
            // set the owning side to null (unless already changed)
            if ($detailHeure->getOrdre() === $this) {
                $detailHeure->setOrdre(null);
            }
        }

        return $this;
    }
}
