<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    /**
     * @Assert\NotNull(message="Ce champ est obligatoire !")
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $name;


    /**
     * @Assert\NotNull(message="Ce champ est obligatoire !")
     * @Assert\GreaterThan("today", message="La date de départ doit etre supérieur à la date d'aujourd'hui.")
     */
    #[ORM\Column(type: 'datetime')]
    private $dateStart;

    /**
     * @Assert\NotNull(message="Ce champ est obligatoire !")
     * @Assert\GreaterThan(0, message="La durée doit etre supérieur à 0.")
     */
    #[ORM\Column(type: 'integer')]
    private $duration;

    /**
     * @Assert\NotNull(message="Ce champ est obligatoire !")
     * @Assert\GreaterThan("today", message="La date limite d'inscription doit etre supérieur à la date d'aujourd'hui.")
     * @Assert\LessThan(propertyPath="dateStart", message="La date limite d'inscription doit etre avant la date de début de l'activitée.")
     */
    #[ORM\Column(type: 'datetime')]
    private $subLimitDate;

    /**
     * @Assert\NotNull(message="Ce champ est obligatoire !")
     * @Assert\GreaterThan(0, message="Le nomre maximum de participant doit etre supérieur à 0.")
     */
    #[ORM\Column(type: 'integer')]
    private $subMax;

    #[ORM\Column(type: 'text')]
    private $infoActivity;

    /**
     * @Assert\NotNull(message="Ce champ est obligatoire !")
     */
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

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'activities', cascade: ['persist', 'remove'])]
    private $users;

    #[ORM\Column(length: 255)]
    private ?string $activityPicture;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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

    public function getisPublished(): ?bool
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

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addActivity($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeActivity($this);
        }

        return $this;
    }

//    /**
//     * @Assert\Callback()
//     */
//    public function validateName(ExecutionContextInterface $context)
//    {
//
//        $name =$this->getName();
//
//        if ($name = null) {
//            $context
//                ->buildViolation('Ce champ ne peut pas etre null.')
//                ->atPath('dateStart')
//                ->addViolation();
//        }
//    }
//
//
//
//    /**
//     * @Assert\Callback
//     */
//    public function validateDateStart(ExecutionContextInterface $context)
//    {
//        // Ensure that $dateStart is in the future compared to now
//        if ($this->dateStart <= new \DateTime()) {
//            $context
//                ->buildViolation('La date de début doit etre posterieur a la date d\'aujourd\'hui.')
//                ->atPath('dateStart')
//                ->addViolation();
//        }
//
//        if ($this->dateStart = null) {
//            $context
//                ->buildViolation('Ce champ ne peut pas etre null.')
//                ->atPath('dateStart')
//                ->addViolation();
//        }
//    }
//
//
//    /**
//     * @Assert\Callback
//     */
//    public function validateDuration(ExecutionContextInterface $context)
//    {
//
//        if ($this->duration = null) {
//            $context
//                ->buildViolation('Ce champ ne peut pas etre null.')
//                ->atPath('dateStart')
//                ->addViolation();
//        }
//        // Ensure that $duration is not negative
//        if ($this->duration < 0) {
//            $context
//                ->buildViolation('La durée ne peu pas etre négative.')
//                ->atPath('duration')
//                ->addViolation();
//        }
//
//        // Ensure that $duration is not negative
//        if ($this->duration > 10080) {
//            $context
//                ->buildViolation('La durée ne peu pas etre supérieur à une semaine.')
//                ->atPath('duration')
//                ->addViolation();
//        }
//    }
//
//
//
//
//    /**
//     * @Assert\Callback
//     */
//    public function validateSubLimitDate(ExecutionContextInterface $context)
//    {
//
//        if ($this->subLimitDate = null) {
//            $context
//                ->buildViolation('Ce champ ne peut pas etre null.')
//                ->atPath('dateStart')
//                ->addViolation();
//        }
//
////         Ensure that $subLimitDate is after today's date
//        if ($this->subLimitDate <= new \DateTime()) {
//            $context
//                ->buildViolation('La date de début d\'inscription doit etre après la date d\'ajourd\'hui.')
//                ->atPath('subLimitDate')
//                ->addViolation();
//        }
//
//        // Ensure that $subLimitDate is before $dateStart
//        if ($this->subLimitDate >= $this->dateStart) {
//            $context
//                ->buildViolation('La date d\'inscription doit etre avant la date du début de l\'activité.')
//                ->atPath('subLimitDate')
//                ->addViolation();
//        }
//    }
//
//    /**
//     * @Assert\Callback
//     */
//    public function validateSubMax(ExecutionContextInterface $context)
//    {
//
//        if ($this->subMax = null) {
//            $context
//                ->buildViolation('Ce champ ne peut pas etre null.')
//                ->atPath('dateStart')
//                ->addViolation();
//        }
//
//        if ($this->subMax < 0) {
//            $context
//                ->buildViolation('Ce champ ne peut pas etre négatif.')
//                ->atPath('dateStart')
//                ->addViolation();
//        }
//    }

public function getActivityPicture(): ?string
{
    return $this->activityPicture;
}

public function setActivityPicture(string $activityPicture): static
{
    $this->activityPicture = $activityPicture;

    return $this;
}

    public function getRemainingPlaces(): int
    {
        return $this->subMax - count($this->users);
    }
}

