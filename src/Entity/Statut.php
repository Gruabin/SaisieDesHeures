<?php

namespace App\Entity;

use App\Repository\StatutRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatutRepository::class)]
class Statut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    /**
     * @var Collection<int,DetailHeures>
     */
    #[ORM\OneToMany(targetEntity: DetailHeures::class, mappedBy: 'statut')]
    private Collection $detail;

    public function __construct()
    {
        $this->detail = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection<int, DetailHeures>
     */
    public function getDetail(): Collection
    {
        return $this->detail;
    }

    public function addDetail(DetailHeures $detail): static
    {
        if (!$this->detail->contains($detail)) {
            $this->detail->add($detail);
            $detail->setStatut($this);
        }

        return $this;
    }

    public function removeDetail(DetailHeures $detail): static
    {
        if ($this->detail->removeElement($detail)) {
            // set the owning side to null (unless already changed)
            if ($detail->getStatut() === $this) {
                $detail->setStatut(null);
            }
        }

        return $this;
    }
}
