<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'datetime')]
    private $dateStart;

    #[ORM\Column(type: 'integer')]
    private $duration;

    #[ORM\Column(type: 'datetime')]
    private $subLimitDate;

    #[ORM\Column(type: 'integer')]
    private $subMax;

    #[ORM\Column(type: 'text')]
    private $infoActivity;

    #[ORM\Column(type: 'boolean')]
    private $isPublished;

    #[ORM\ManyToOne(targetEntity: Status::class, inversedBy: 'activities')]
    #[ORM\JoinColumn(nullable: false)]
    private $status;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'activities')]
    #[ORM\JoinColumn(nullable: false)]
    private $location;

    #[ORM\ManyToOne(targetEntity: Campus::class, inversedBy: 'activities')]
    #[ORM\JoinColumn(nullable: false)]
    private $campus;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'activitiesOrganized')]
    #[ORM\JoinColumn(nullable: false)]
    private $organizer;

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

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getSubLimitDate(): ?\DateTimeInterface
    {
        return $this->subLimitDate;
    }

    public function setSubLimitDate(\DateTimeInterface $subLimitDate): self
    {
        $this->subLimitDate = $subLimitDate;

        return $this;
    }

    public function getSubMax(): ?int
    {
        return $this->subMax;
    }

    public function setSubMax(int $subMax): self
    {
        $this->subMax = $subMax;

        return $this;
    }

    public function getInfoActivity(): ?string
    {
        return $this->infoActivity;
    }

    public function setInfoActivity(string $infoActivity): self
    {
        $this->infoActivity = $infoActivity;

        return $this;
    }

    public function isIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function getOrganizer(): ?User
    {
        return $this->organizer;
    }

    public function setOrganizer(?User $organizer): self
    {
        $this->organizer = $organizer;

        return $this;
    }
}
