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
    private string $id;

    /**
     * @var Collection<int,Employe>
     */
    #[ORM\OneToMany(mappedBy: 'centre_de_charge', targetEntity: Employe::class)]
    private Collection $employes;

    /**
     * @var Collection<int,DetailHeures>
     */
    #[ORM\OneToMany(mappedBy: 'centre_de_charge', targetEntity: DetailHeures::class)]
    private Collection $detailHeures;

    #[ORM\ManyToOne(inversedBy: 'responsable')]
    private ?Employe $responsable = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;

    public function __construct()
    {
        $this->employes = new ArrayCollection();
        $this->detailHeures = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        $name = [
            $this->id,
        ];

        return implode(' - ', array_filter($name));
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

    /**
     * @return Collection<int, DetailHeures>
     */
    public function getDetailHeures(): Collection
    {
        return $this->detailHeures;
    }

    public function addDetailHeure(DetailHeures $detailHeure): static
    {
        if (!$this->detailHeures->contains($detailHeure)) {
            $this->detailHeures->add($detailHeure);
            $detailHeure->setCentreDeCharge($this);
        }

        return $this;
    }

    public function removeDetailHeure(DetailHeures $detailHeure): static
    {
        if ($this->detailHeures->removeElement($detailHeure)) {
            // set the owning side to null (unless already changed)
            if ($detailHeure->getCentreDeCharge() === $this) {
                $detailHeure->setCentreDeCharge(null);
            }
        }

        return $this;
    }

    public function getResponsable(): ?Employe
    {
        return $this->responsable;
    }

    public function setResponsable(?Employe $responsable): static
    {
        $this->responsable = $responsable;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }
}
