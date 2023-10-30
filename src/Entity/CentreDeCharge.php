<?php

namespace App\Entity;

use App\Repository\CentreDeChargeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CentreDeChargeRepository::class)]
class CentreDeCharge
{
    #[ORM\Id]
    #[ORM\Column(length: 255)]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $descriptionCdg = null;

    #[ORM\OneToMany(mappedBy: 'centreDeCharge', targetEntity: Employe::class)]
    private $employes;

    #[ORM\OneToMany(mappedBy: 'centreDeCharge', targetEntity: DetailHeures::class)]
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

    public function getDescriptionCdg(): ?string
    {
        return $this->descriptionCdg;
    }

    public function setDescriptionCdg(string $descriptionCdg): static
    {
        $this->descriptionCdg = $descriptionCdg;

        return $this;
    }

    public function getEmployes(): ?Employe
    {
        return $this->employes;
    }

    public function addEmploye(Employe $employe): static
    {
        if (!$this->employes->contains($employe)) {
            $this->employes->add($employe);
            $employe->setCentreDeCharge($this);
        }

        return $this;
    }

    public function removeEmploye(Employe $employe): static
    {
        if ($this->employes->removeElement($employe)) {
            // set the owning side to null (unless already changed)
            if ($employe->getCentreDeCharge() === $this) {
                $employe->setCentreDeCharge(null);
            }
        }

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
            $detailHeure->addCentreDeCharge($this);
        }

        return $this;
    }

    public function removeDetailHeure(DetailHeures $detailHeure): static
    {
        if ($this->detailHeures->removeElement($detailHeure)) {
            $detailHeure->removeCentreDeCharge($this);
        }

        return $this;
    }
}
