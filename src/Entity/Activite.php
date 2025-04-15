<?php

namespace App\Entity;

use App\Repository\ActiviteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActiviteRepository::class)]
class Activite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private ?string $description_activite = null;

    /**
     * @var Collection<int,DetailHeures>
     */
    #[ORM\OneToMany(mappedBy: 'activite', targetEntity: DetailHeures::class)]
    private Collection $detailHeures;

    public function __construct()
    {
        $this->detailHeures = new ArrayCollection();
    }

    public function getId(): int
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
            $this->description_activite,
        ];

        return implode(' - ', array_filter($name));
    }

    public function getDescriptionActivite(): ?string
    {
        return $this->description_activite;
    }

    public function setDescriptionActivite(string $description_activite): static
    {
        $this->description_activite = $description_activite;

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
