<?php

namespace App\Entity;

use App\Repository\CentreDeChargeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CentreDeChargeRepository::class)]
class CentreDeCharge
{
    #[ORM\Id]
    #[ORM\Column(length: 255)]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description_CDG = null;

    #[ORM\OneToMany(mappedBy: 'centre_de_charge', targetEntity: Employe::class)]
    private Collection $employes;

    public function __construct()
    {
        $this->employes = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }
    
    public function getDescriptionCDG(): ?string
    {
        return $this->description_CDG;
    }

    public function setDescriptionCDG(string $description_CDG): static
    {
        $this->description_CDG = $description_CDG;

        return $this;
    }

    /**
     * @return Collection<int, Employe>
     */
    public function getEmployes(): Collection
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
}
