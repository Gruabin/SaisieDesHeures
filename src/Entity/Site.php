<?php

namespace App\Entity;

use App\Repository\SiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SiteRepository::class)]
class Site
{
    #[ORM\Id]
    #[ORM\Column]
    private ?string $id = null;

    /**
     * @var Collection<string, TacheSpecifique>
     */
    #[ORM\ManyToMany(targetEntity: TacheSpecifique::class, inversedBy: 'sites')]
    private Collection $tacheSpecifique;

    public function __construct()
    {
        $this->tacheSpecifique = new ArrayCollection();
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

    /**
     * @return Collection<string, TacheSpecifique>
     */
    public function getTacheSpecifique(): Collection
    {
        return $this->tacheSpecifique;
    }

    public function addTacheSpecifique(TacheSpecifique $tacheSpecifique): static
    {
        if (!$this->tacheSpecifique->contains($tacheSpecifique)) {
            $this->tacheSpecifique->add($tacheSpecifique);
        }

        return $this;
    }

    public function removeTacheSpecifique(TacheSpecifique $tacheSpecifique): static
    {
        $this->tacheSpecifique->removeElement($tacheSpecifique);

        return $this;
    }
}
