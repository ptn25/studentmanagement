<?php

namespace App\Entity;

use App\Repository\SubjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubjectRepository::class)]
class Subject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $image;

    #[ORM\OneToMany(mappedBy: 'subjId', targetEntity: Mark::class)]
    private $marks;

    #[ORM\ManyToMany(targetEntity: Lecturer::class, mappedBy: 'subjId')]
    private $lecturers;

    public function __construct()
    {
        $this->marks = new ArrayCollection();
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
     * @return Collection<int, Mark>
     */
    public function getMarks(): Collection
    {
        return $this->marks;
    }

    public function addMark(Mark $mark): self
    {
        if (!$this->marks->contains($mark)) {
            $this->marks[] = $mark;
            $mark->setSubjId($this);
        }

        return $this;
    }

    public function removeMark(Mark $mark): self
    {
        if ($this->marks->removeElement($mark)) {
            // set the owning side to null (unless already changed)
            if ($mark->getSubjId() === $this) {
                $mark->setSubjId(null);
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
            $lecturer->addSubjId($this);
        }

        return $this;
    }

    public function removeLecturer(Lecturer $lecturer): self
    {
        if ($this->lecturers->removeElement($lecturer)) {
            $lecturer->removeSubjId($this);
        }

        return $this;
    }
}
