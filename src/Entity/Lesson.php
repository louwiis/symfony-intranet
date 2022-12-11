<?php

namespace App\Entity;

use App\Repository\LessonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LessonRepository::class)]
class Lesson
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private string $name;

    #[ORM\Column]
    private string $teacher;

    #[ORM\Column]
    private string $room;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $date;

    #[ORM\Column(type: 'time')]
    private \DateTimeInterface $startTime;

    #[ORM\Column(type: 'time')]
    private \DateTimeInterface $endTime;

    #[ORM\ManyToOne(targetEntity: Classe::class, inversedBy: 'lessons', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Classe $classe;

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

    public function getTeacher(): string
    {
        return $this->teacher;
    }

    public function setTeacher(string $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }

    public function getRoom(): string
    {
        return $this->room;
    }

    public function setRoom(string $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getStartTime(): \DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): \DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getClasse(): Classe
    {
        return $this->classe;
    }

    public function setClasse(Classe $classe): self
    {
        $this->classe = $classe;

        return $this;
    }
}
