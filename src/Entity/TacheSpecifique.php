<?php

namespace App\Entity;

use App\Repository\TacheSpecifiqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TacheSpecifiqueRepository::class)]
class TacheSpecifique
{
    #[ORM\Id]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: Site::class, mappedBy: 'tacheSpecifique')]
    private Collection $sites;

    #[ORM\OneToMany(mappedBy: 'tacheSpecifique', targetEntity: DetailHeures::class)]
    private Collection $detailHeure;

    public function __construct()
    {
        $this->sites = new ArrayCollection();
        $this->detailHeure = new ArrayCollection();
    }

    public function getName(): ?string
    {
        $name = [
            $this->id,
            $this->description,
        ];

        return implode(' - ', array_filter($name));
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Site>
     */
    public function getSites(): Collection
    {
        return $this->sites;
    }

    public function addSite(Site $site): static
    {
        if (!$this->sites->contains($site)) {
            $this->sites->add($site);
            $site->addTacheSpecifique($this);
        }

        return $this;
    }

    public function removeSite(Site $site): static
    {
        if ($this->sites->removeElement($site)) {
            $site->removeTacheSpecifique($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, DetailHeures>
     */
    public function getDetailHeure(): Collection
    {
        return $this->detailHeure;
    }

    public function addDetailHeure(DetailHeures $detailHeure): static
    {
        if (!$this->detailHeure->contains($detailHeure)) {
            $this->detailHeure->add($detailHeure);
            $detailHeure->setTacheSpecifique($this);
        }

        return $this;
    }

    public function removeDetailHeure(DetailHeures $detailHeure): static
    {
        if ($this->detailHeure->removeElement($detailHeure)) {
            // set the owning side to null (unless already changed)
            if ($detailHeure->getTacheSpecifique() === $this) {
                $detailHeure->setTacheSpecifique(null);
            }
        }

        return $this;
    }
}
