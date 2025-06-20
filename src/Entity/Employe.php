<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EmployeRepository::class)]
class Employe implements UserInterface
{
    #[ORM\Id]
    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 9,
        max: 9,
    )]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_employe = null;

    #[ORM\ManyToOne(inversedBy: 'employes')]
    private ?CentreDeCharge $centre_de_charge = null;

    /**
     * @var Collection<int,DetailHeures>
     */
    #[ORM\OneToMany(mappedBy: 'employe', targetEntity: DetailHeures::class)]
    private Collection $detailHeures;

    /**
     * @var Collection<int,CentreDeCharge>
     */
    #[ORM\OneToMany(targetEntity: CentreDeCharge::class, mappedBy: 'responsable')]
    private Collection $responsable;

    /**
     * @var array<string>
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var Collection<int,FavoriTypeHeure>
     */
    #[ORM\OneToMany(targetEntity: FavoriTypeHeure::class, mappedBy: 'employe')]
    private Collection $favoriTypeHeures;

    public function __construct()
    {
        $this->detailHeures = new ArrayCollection();
        $this->responsable = new ArrayCollection();
        $this->favoriTypeHeures = new ArrayCollection();
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        // $roles[] = 'ROLE_EMPLOYE';
        return array_unique($roles);
    }

    /**
     * Summary of setRoles.
     *
     * @param array<string> $roles
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
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
        return $this->nom_employe;
    }

    public function setNomEmploye(string $nom_employe): static
    {
        $this->nom_employe = $nom_employe;

        return $this;
    }

    public function getCentreDeCharge(): ?CentreDeCharge
    {
        return $this->centre_de_charge;
    }

    public function setCentreDeCharge(?CentreDeCharge $centre_de_charge): static
    {
        $this->centre_de_charge = $centre_de_charge;

        return $this;
    }

    /**
     * @return Collection<int, DetailHeures>
     */
    public function getDetailHeures(): Collection
    {
        return $this->detailHeures;
    }

    public function addDetailheure(DetailHeures $detailheure): static
    {
        if (!$this->detailHeures->contains($detailheure)) {
            $this->detailHeures->add($detailheure);
            $detailheure->setEmploye($this);
        }

        return $this;
    }

    public function removeDetailheure(DetailHeures $detailheure): static
    {
        if ($this->detailHeures->removeElement($detailheure)) {
            // set the owning side to null (unless already changed)
            if ($detailheure->getEmploye() === $this) {
                $detailheure->setEmploye(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CentreDeCharge>
     */
    public function getResponsable(): Collection
    {
        return $this->responsable;
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
            $favoriTypeHeure->setEmploye($this);
        }

        return $this;
    }

    public function removeFavoriTypeHeure(FavoriTypeHeure $favoriTypeHeure): static
    {
        if ($this->favoriTypeHeures->removeElement($favoriTypeHeure)) {
            // set the owning side to null (unless already changed)
            if ($favoriTypeHeure->getEmploye() === $this) {
                $favoriTypeHeure->setEmploye(null);
            }
        }

        return $this;
    }
}
