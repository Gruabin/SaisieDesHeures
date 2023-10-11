<?php

namespace App\Entity;

use App\Repository\OrdreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdreRepository::class)]
class Ordre
{
    #[ORM\Id]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description_ordre = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }
    public function getDescriptionOrdre(): ?string
    {
        return $this->description_ordre;
    }

    public function setDescriptionOrdre(string $description_ordre): static
    {
        $this->description_ordre = $description_ordre;

        return $this;
    }
}
