<?php

namespace App\Entity;

use App\Entity\School;
use App\Repository\ClasseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClasseRepository::class)]
class Classe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private string $name;

    #[ORM\OneToMany(mappedBy: 'classe', targetEntity: Student::class)]
    private $students;

    #[ORM\ManyToOne(targetEntity: School::class, inversedBy: 'classes', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private School $school;

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

    public function getStudents()
    {
        return $this->students;
    }

    public function setStudents($students): self
    {
        $this->students = $students;

        return $this;
    }

    public function getSchool(): School
    {
        return $this->school;
    }

    public function setSchool(School $school): self
    {
        $this->school = $school;

        return $this;
    }
}
