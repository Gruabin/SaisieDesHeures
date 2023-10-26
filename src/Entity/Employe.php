<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: EmployeRepository::class)]
class Employe implements UserInterface
{
    #[ORM\Id]
    #[ORM\Column(length: 255)]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomEmploye = null;

    #[ORM\ManyToOne(targetEntity: CentreDeCharge::class, inversedBy: 'employes')]
    private $centreDeCharge;

    public function __construct()
    {
        $this->centreDeCharge = new CentreDeCharge();
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): string
    {
        return '';
    }

    public function getUserIdentifier(): string
    {
        return $this->id;
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

    public function getNomEmploye(): ?string
    {
        return $this->nomEmploye;
    }

    public function setNomEmploye(string $nomEmploye): static
    {
        $this->nomEmploye = $nomEmploye;

        return $this;
    }

    public function getCentreDeCharge(): ?CentreDeCharge
    {
        return $this->centreDeCharge;
    }

    public function setCentreDeCharge(?CentreDeCharge $centreDeCharge): static
    {
        $this->centreDeCharge = $centreDeCharge;

        return $this;
    }
}
