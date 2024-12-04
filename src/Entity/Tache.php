<?php

namespace App\Entity;

use App\Repository\TacheRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TacheRepository::class)]
class Tache
{
    #[ORM\Id]
    // #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_tache = null;

    #[ORM\OneToMany(mappedBy: 'tache', targetEntity: DetailHeures::class)]
    private Collection $detailHeures;

    #[ORM\ManyToOne(inversedBy: 'taches')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeHeures $typeHeures = null;

    public function __construct()
    {
        $this->detailHeures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        $name = [
            $this->id,
            $this->nom_tache,
        ];

        return implode(' - ', array_filter($name));
    }

    public function getNomTache(): ?string
    {
        return $this->nom_tache;
    }

    public function setNomTache(string $nom_tache): static
    {
        $this->nom_tache = $nom_tache;

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

    public function getTypeHeures(): ?TypeHeures
    {
        return $this->typeHeures;
    }

    public function setTypeHeures(?TypeHeures $typeHeures): static
    {
        $this->typeHeures = $typeHeures;

        return $this;
    }
}
