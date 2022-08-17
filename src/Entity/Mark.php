<?php

namespace App\Entity;

use App\Repository\MarkRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MarkRepository::class)]
class Mark
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $mark;

    #[ORM\ManyToOne(targetEntity: Student::class, inversedBy: 'marks')]
    private $stuId;

    #[ORM\ManyToOne(targetEntity: Subject::class, inversedBy: 'marks')]
    private $subjId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMark(): ?string
    {
        return $this->mark;
    }

    public function setMark(string $mark): self
    {
        $this->mark = $mark;

        return $this;
    }

    public function getStuId(): ?Student
    {
        return $this->stuId;
    }

    public function setStuId(?Student $stuId): self
    {
        $this->stuId = $stuId;

        return $this;
    }

    public function getSubjId(): ?Subject
    {
        return $this->subjId;
    }

    public function setSubjId(?Subject $subjId): self
    {
        $this->subjId = $subjId;

        return $this;
    }
}
