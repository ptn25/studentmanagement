<?php

namespace App\Entity;

use App\Repository\ClassesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClassesRepository::class)]
class Classes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'classId', targetEntity: Student::class)]
    private $students;

    #[ORM\ManyToMany(targetEntity: Lecturer::class, mappedBy: 'classId')]
    private $lecturers;

    public function __construct()
    {
        $this->students = new ArrayCollection();
        $this->lecturers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): self
    {
        if (!$this->students->contains($student)) {
            $this->students[] = $student;
            $student->setClassId($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getClassId() === $this) {
                $student->setClassId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Lecturer>
     */
    public function getLecturers(): Collection
    {
        return $this->lecturers;
    }

    public function addLecturer(Lecturer $lecturer): self
    {
        if (!$this->lecturers->contains($lecturer)) {
            $this->lecturers[] = $lecturer;
            $lecturer->addClassId($this);
        }

        return $this;
    }

    public function removeLecturer(Lecturer $lecturer): self
    {
        if ($this->lecturers->removeElement($lecturer)) {
            $lecturer->removeClassId($this);
        }

        return $this;
    }
}
