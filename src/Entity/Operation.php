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
    private ?string $descriptionOperation = null;

    #[ORM\OneToMany(mappedBy: 'operation', targetEntity: DetailHeures::class)]
    private $detailHeures;

    public function __construct()
    {
        $this->detailHeures = new DetailHeures();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescriptionOperation(): ?string
    {
        return $this->descriptionOperation;
    }

    public function setDescriptionOperation(string $descriptionOperation): static
    {
        $this->descriptionOperation = $descriptionOperation;

        return $this;
    }

    public function getDetailHeures(): ?DetailHeures
    {
        return $this->detailHeures;
    }

    public function addDetailHeure(DetailHeures $detailHeure): static
    {
        if (!$this->detailHeures->contains($detailHeure)) {
            $this->detailHeures->add($detailHeure);
            $detailHeure->setOperation($this);
        }

        return $this;
    }

    public function removeDetailHeure(DetailHeures $detailHeure): static
    {
        if ($this->detailHeures->removeElement($detailHeure)) {
            // set the owning side to null (unless already changed)
            if ($detailHeure->getOperation() === $this) {
                $detailHeure->setOperation(null);
            }
        }

        return $this;
    }
}
