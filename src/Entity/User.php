<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use http\Message;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', length: 255)]
    private $pseudo;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(
        max: 10,
        maxMessage: "Le nom de famille ne peut pas dépasser {{ limit }} caractères."
    )]
    #[Assert\NotBlank(message: "Le nom de famille ne peut pas être vide.")]
    private $lastname;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(
        max: 10,
        maxMessage: "Le prénom ne peut pas dépasser {{ limit }} caractères."
    )]
    #[Assert\NotBlank(message: "Le prénom ne peut pas être vide.")]
    private $firstname;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Regex(
        pattern: '/^0[1-9] \d{2} \d{2} \d{2} \d{2}$/',
        message: "Le numéro de téléphone doit être au format (0X XX XX XX XX)."
    )]
    private $phone;


    #[ORM\Column(type: 'boolean')]
    private $activeStatus;

    #[ORM\Column(type: 'string', length: 255)]
    private $profilePicture;

    #[ORM\ManyToOne(targetEntity: Campus::class, inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private $campus;

    #[ORM\OneToMany(mappedBy: 'organizer', targetEntity: Activity::class, orphanRemoval: true)]
    private $activitiesOrganized;

    #[ORM\ManyToMany(targetEntity: Activity::class, inversedBy: 'users', cascade: ['persist', 'remove'])]
    private $activities;

    public function __construct()
    {
        $this->activitiesOrganized = new ArrayCollection();
        $this->activities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getFullName(): string
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function isActiveStatus(): ?bool
    {
        return $this->activeStatus;
    }

    public function setActiveStatus(bool $activeStatus): self
    {
        $this->activeStatus = $activeStatus;

        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(string $profilePicture): self
    {
        $this->profilePicture = $profilePicture;

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

    /**
     * @return Collection<int, Activity>
     */
    public function getActivitiesOrganized(): Collection
    {
        return $this->activitiesOrganized;
    }

    public function addActivitiesOrganized(Activity $activitiesOrganized): self
    {
        if (!$this->activitiesOrganized->contains($activitiesOrganized)) {
            $this->activitiesOrganized[] = $activitiesOrganized;
            $activitiesOrganized->setOrganizer($this);
        }

        return $this;
    }

    public function removeActivitiesOrganized(Activity $activitiesOrganized): self
    {
        if ($this->activitiesOrganized->removeElement($activitiesOrganized)) {
            // set the owning side to null (unless already changed)
            if ($activitiesOrganized->getOrganizer() === $this) {
                $activitiesOrganized->setOrganizer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Activity>
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): self
    {
        if (!$this->activities->contains($activity)) {
            $this->activities[] = $activity;
        }

        return $this;
    }

    public function removeActivity(Activity $activity): self
    {
        $this->activities->removeElement($activity);

        return $this;
    }

    public function __toString()
    {
        return $this->getPseudo();
    }


}
