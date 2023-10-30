<?php

namespace App\Entity;

use App\Repository\ActiviteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActiviteRepository::class)]
class Activite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $descriptionActivite = null;

    #[ORM\OneToMany(mappedBy: 'activite', targetEntity: DetailHeures::class)]
    private ?DetailHeures $detailHeures;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescriptionActivite(): ?string
    {
        return $this->descriptionActivite;
    }

    public function setDescriptionActivite(string $descriptionActivite): static
    {
        $this->descriptionActivite = $descriptionActivite;

        return $this;
    }

    public function getDetailHeures(): DetailHeures
    {
        return $this->detailHeures;
    }

    public function addDetailHeure(DetailHeures $detailHeure): static
    {
        if (!$this->detailHeures->contains($detailHeure)) {
            $this->detailHeures->add($detailHeure);
            $detailHeure->setActivite($this);
        }

        return $this;
    }

    public function removeDetailHeure(DetailHeures $detailHeure): static
    {
        if ($this->detailHeures->removeElement($detailHeure)) {
            // set the owning side to null (unless already changed)
            if ($detailHeure->getActivite() === $this) {
                $detailHeure->setActivite(null);
            }
        }

        return $this;
    }
}
