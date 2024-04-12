<?php

namespace App\Entity;

use App\Repository\ExpensesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExpensesRepository::class)]
class Expenses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $expenseType = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $TotalAmount = null;

    #[ORM\ManyToOne(inversedBy: 'Expenses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trips $trips = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExpenseType(): ?string
    {
        return $this->expenseType;
    }

    public function setExpenseType(string $expenseType): static
    {
        $this->expenseType = $expenseType;

        return $this;
    }

    public function getTotalAmount(): ?string
    {
        return $this->TotalAmount;
    }

    public function setTotalAmount(string $TotalAmount): static
    {
        $this->TotalAmount = $TotalAmount;

        return $this;
    }

    public function getTrips(): ?Trips
    {
        return $this->trips;
    }

    public function setTrips(?Trips $trips): static
    {
        $this->trips = $trips;

        return $this;
    }
}
