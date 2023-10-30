<?php

namespace App\Entity;

use App\Repository\TacheRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TacheRepository::class)]
class Tache
{
    #[ORM\Id]
    // #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomTache = null;

    #[ORM\OneToMany(mappedBy: 'tache', targetEntity: DetailHeures::class)]
    private $detailHeures;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getNomTache(): ?string
    {
        return $this->nomTache;
    }

    public function setNomTache(string $nomTache): static
    {
        $this->nomTache = $nomTache;

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
            $detailHeure->setTache($this);
        }

        return $this;
    }

    public function removeDetailHeure(DetailHeures $detailHeure): static
    {
        if ($this->detailHeures->removeElement($detailHeure)) {
            // set the owning side to null (unless already changed)
            if ($detailHeure->getTache() === $this) {
                $detailHeure->setTache(null);
            }
        }

        return $this;
    }
}
