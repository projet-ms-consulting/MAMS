<?php

namespace App\Entity;

use App\Repository\TripsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TripsRepository::class)]
class Trips
{
    #[ORM\Id]
    #[ORM\GeneratedValue]

    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $tripDate = null;

    #[ORM\Column(length: 100)]
    private ?string $origin = null;

    #[ORM\Column(length: 100)]
    private ?string $destination = null;

    #[ORM\Column]
    private ?int $mileage = null;

    #[ORM\Column(length: 10)]
    private ?string $unit = null;

    #[ORM\Column(length: 100)]
    private ?string $context = null;

    #[ORM\Column(length: 100)]
    private ?string $category = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $billableClient = null;

    #[ORM\OneToMany(targetEntity: Expenses::class, mappedBy: 'trips')]
    private Collection $Expenses;

    #[ORM\ManyToOne(inversedBy: 'trips')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: Vehicle::class, inversedBy: 'trips')]
    private Collection $vehicle;

    public function __construct()
    {
        $this->Expenses = new ArrayCollection();
        $this->vehicle = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTripDate(): ?\DateTimeInterface
    {
        return $this->tripDate;
    }

    public function setTripDate(\DateTimeInterface $tripDate): static
    {
        $this->tripDate = $tripDate;

        return $this;
    }

    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    public function setOrigin(string $origin): static
    {
        $this->origin = $origin;

        return $this;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): static
    {
        $this->destination = $destination;

        return $this;
    }

    public function getMileage(): ?int
    {
        return $this->mileage;
    }

    public function setMileage(int $mileage): static
    {
        $this->mileage = $mileage;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function setContext(string $context): static
    {
        $this->context = $context;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

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

    public function isBillableClient(): ?bool
    {
        return $this->billableClient;
    }

    public function setBillableClient(bool $billableClient): static
    {
        $this->billableClient = $billableClient;

        return $this;
    }

    /**
     * @return Collection<int, Expenses>
     */
    public function getExpenses(): Collection
    {
        return $this->Expenses;
    }

    public function addExpense(Expenses $expense): static
    {
        if (!$this->Expenses->contains($expense)) {
            $this->Expenses->add($expense);
            $expense->setTrips($this);
        }

        return $this;
    }

    public function removeExpense(Expenses $expense): static
    {
        if ($this->Expenses->removeElement($expense)) {
            // set the owning side to null (unless already changed)
            if ($expense->getTrips() === $this) {
                $expense->setTrips(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Vehicle>
     */
    public function getVehicle(): Collection
    {
        return $this->vehicle;
    }

    public function addVehicle(Vehicle $vehicle): static
    {
        if (!$this->vehicle->contains($vehicle)) {
            $this->vehicle->add($vehicle);
        }

        return $this;
    }

    public function removeVehicle(Vehicle $vehicle): static
    {
        $this->vehicle->removeElement($vehicle);

        return $this;
    }
}
