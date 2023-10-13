<?php

namespace App\Entity;

use App\Repository\OperationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OperationRepository::class)]
class Operation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description_operation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescriptionOperation(): ?string
    {
        return $this->description_operation;
    }

    public function setDescriptionOperation(string $description_operation): static
    {
        $this->description_operation = $description_operation;

        return $this;
    }
}
