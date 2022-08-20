<?php

namespace App\Entity;

use App\Entity\Classes;
use App\Entity\Subject;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\LecturerRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: LecturerRepository::class)]
class Lecturer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'date')]
    private $dob;

    #[ORM\Column(type: 'string', length: 255)]
    private $image;

    #[ORM\ManyToMany(targetEntity: Classes::class, inversedBy: 'lecturers')]
    private $classId;

    #[ORM\ManyToMany(targetEntity: Subject::class, inversedBy: 'lecturers')]
    private $subjId;
    public function __toString()
    {
        return $this -> image;
    }
    public function __construct()
    {
        $this->classId = new ArrayCollection();
        $this->subjId = new ArrayCollection();
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

    public function getDob(): ?\DateTimeInterface
    {
        return $this->dob;
    }

    public function setDob(\DateTimeInterface $dob): self
    {
        $this->dob = $dob;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Classes>
     */
    public function getClassId(): Collection
    {
        return $this->classId;
    }

    public function addClassId(Classes $classId): self
    {
        if (!$this->classId->contains($classId)) {
            $this->classId[] = $classId;
        }

        return $this;
    }

    public function removeClassId(Classes $classId): self
    {
        $this->classId->removeElement($classId);

        return $this;
    }

    /**
     * @return Collection<int, Subject>
     */
    public function getSubjId(): Collection
    {
        return $this->subjId;
    }

    public function addSubjId(Subject $subjId): self
    {
        if (!$this->subjId->contains($subjId)) {
            $this->subjId[] = $subjId;
        }

        return $this;
    }

    public function removeSubjId(Subject $subjId): self
    {
        $this->subjId->removeElement($subjId);

        return $this;
    }
}
