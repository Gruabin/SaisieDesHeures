<?php

namespace App\Entity;

use App\Repository\ActiviteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActiviteRepository::class)]
class Activite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description_activite = null;

    public function getId(): ?int
    {
        return $this->id;
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
}
