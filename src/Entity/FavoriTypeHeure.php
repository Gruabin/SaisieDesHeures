<?php

namespace App\Entity;

use App\Repository\FavoriTypeHeureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: FavoriTypeHeureRepository::class)]
class FavoriTypeHeure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'favoriTypeHeures')]
    private ?TypeHeures $typeHeure = null;

    #[ORM\ManyToOne(inversedBy: 'favoriTypeHeures')]
    private ?Employe $employe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeHeure(): ?TypeHeures
    {
        return $this->typeHeure;
    }

    public function setTypeHeure(?TypeHeures $typeHeure): static
    {
        $this->typeHeure = $typeHeure;

        return $this;
    }

    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(?Employe $employe): static
    {
        $this->employe = $employe;

        return $this;
    }
}
