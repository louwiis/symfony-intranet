<?php

namespace App\Entity;

use App\Entity\Address;
use App\Repository\SchoolRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SchoolRepository::class)]
class School
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private string $name;

    #[ORM\OneToOne(targetEntity: Address::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Address $address;

    #[ORM\OneToMany(mappedBy: 'school', targetEntity: Classe::class)]
    private $classes;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getClasses()
    {
        return $this->classes;
    }

    public function setClasses($classes): self
    {
        $this->classes = $classes;

        return $this;
    }
}
