<?php

namespace App\Entity;

use App\Repository\TypeHeuresRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeHeuresRepository::class)]
class TypeHeures
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private ?string $nom_type = null;

    /**
     * @var Collection<int,DetailHeures>
     */
    #[ORM\OneToMany(mappedBy: 'type_heures', targetEntity: DetailHeures::class)]
    private Collection $detailHeures;


    /**
     * @var Collection<int,Tache>
     */
    #[ORM\OneToMany(mappedBy: 'typeHeures', targetEntity: Tache::class)]
    private Collection $taches;


    /**
     * @var Collection<int,FavoriTypeHeure>
     */
    #[ORM\OneToMany(targetEntity: FavoriTypeHeure::class, mappedBy: 'typeHeure')]
    private Collection $favoriTypeHeures;

    public function __construct()
    {
        $this->detailHeures = new ArrayCollection();
        $this->taches = new ArrayCollection();
        $this->favoriTypeHeures = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getNomType(): ?string
    {
        return $this->nom_type;
    }

    public function setNomType(string $nom_type): static
    {
        $this->nom_type = $nom_type;

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
            $detailHeure->setTypeHeures($this);
        }

        return $this;
    }

    public function removeDetailHeure(DetailHeures $detailHeure): static
    {
        if ($this->detailHeures->removeElement($detailHeure)) {
            // set the owning side to null (unless already changed)
            if ($detailHeure->getTypeHeures() === $this) {
                $detailHeure->setTypeHeures(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tache>
     */
    public function getTaches(): Collection
    {
        return $this->taches;
    }

    public function addTach(Tache $tach): static
    {
        if (!$this->taches->contains($tach)) {
            $this->taches->add($tach);
            $tach->setTypeHeures($this);
        }

        return $this;
    }

    public function removeTach(Tache $tach): static
    {
        if ($this->taches->removeElement($tach)) {
            // set the owning side to null (unless already changed)
            if ($tach->getTypeHeures() === $this) {
                $tach->setTypeHeures(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FavoriTypeHeure>
     */
    public function getFavoriTypeHeures(): Collection
    {
        return $this->favoriTypeHeures;
    }

    public function addFavoriTypeHeure(FavoriTypeHeure $favoriTypeHeure): static
    {
        if (!$this->favoriTypeHeures->contains($favoriTypeHeure)) {
            $this->favoriTypeHeures->add($favoriTypeHeure);
            $favoriTypeHeure->setTypeHeure($this);
        }

        return $this;
    }

    public function removeFavoriTypeHeure(FavoriTypeHeure $favoriTypeHeure): static
    {
        if ($this->favoriTypeHeures->removeElement($favoriTypeHeure)) {
            // set the owning side to null (unless already changed)
            if ($favoriTypeHeure->getTypeHeure() === $this) {
                $favoriTypeHeure->setTypeHeure(null);
            }
        }

        return $this;
    }
}
