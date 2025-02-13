<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\UserInfo;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;



    // #[ORM\OneToOne(targetEntity: "App\Entity\UserInfo", mappedBy: "user", cascade: ["persist", "remove"])]
    // private $userInfo;
    #[ORM\OneToOne(targetEntity: "App\Entity\UserInfo", mappedBy: "user", cascade: ["persist", "remove"])]
    private $userInfo;


    /**
     * @var Collection<int, Units>
     */
    #[ORM\OneToMany(targetEntity: Units::class, mappedBy: 'modifiedBy')]
    private Collection $units;

    /**
     * @var Collection<int, ActivityCode>
     */
    #[ORM\OneToMany(targetEntity: ActivityCode::class, mappedBy: 'modifiedBy')]
    private Collection $activityCodes;

    /**
     * @var Collection<int, Specification>
     */
    #[ORM\OneToMany(targetEntity: Specification::class, mappedBy: 'modifiedBy')]
    private Collection $specifications;

    /**
     * @var Collection<int, Type>
     */
    #[ORM\OneToMany(targetEntity: Type::class, mappedBy: 'modifiedBy')]
    private Collection $types;

    public function __construct()
    {
        // $this->userInfo = new UserInfo(); // Ensure Us
        $this->units = new ArrayCollection();
        $this->activityCodes = new ArrayCollection();
        $this->specifications = new ArrayCollection();
        $this->types = new ArrayCollection();
    }

    public function setUserInfo(UserInfo $userInfo): self
    {
        $this->userInfo = $userInfo;
        if ($userInfo->getUser() !== $this) {
            $userInfo->setUser($this);
        }
        return $this;
    }

    public function getUserInfo(): ?UserInfo
    {
        return $this->userInfo;
    }
    
    public function getName(): string
    {
        return $this->userInfo ? $this->userInfo->getName() : 'No Name';
    }

    // public function setUserInfo(?UserInfo $userInfo): self
    // {
    //     $this->userInfo = $userInfo;

    //     // set (or unset) the owning side of the relation if necessary
    //     if ($userInfo !== null && $userInfo->getUser() !== $this) {
    //         $userInfo->setUser($this);
    //     }

    //     return $this;
    // }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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
        return (string) $this->email;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        // $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Units>
     */
    public function getUnits(): Collection
    {
        return $this->units;
    }

    public function addUnit(Units $unit): static
    {
        if (!$this->units->contains($unit)) {
            $this->units->add($unit);
            $unit->setModifiedBy($this);
        }

        return $this;
    }

    public function removeUnit(Units $unit): static
    {
        if ($this->units->removeElement($unit)) {
            // set the owning side to null (unless already changed)
            if ($unit->getModifiedBy() === $this) {
                $unit->setModifiedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ActivityCode>
     */
    public function getActivityCodes(): Collection
    {
        return $this->activityCodes;
    }

    public function addActivityCode(ActivityCode $activityCode): static
    {
        if (!$this->activityCodes->contains($activityCode)) {
            $this->activityCodes->add($activityCode);
            $activityCode->setModifiedBy($this);
        }

        return $this;
    }

    public function removeActivityCode(ActivityCode $activityCode): static
    {
        if ($this->activityCodes->removeElement($activityCode)) {
            // set the owning side to null (unless already changed)
            if ($activityCode->getModifiedBy() === $this) {
                $activityCode->setModifiedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Specification>
     */
    public function getSpecifications(): Collection
    {
        return $this->specifications;
    }

    public function addSpecification(Specification $specification): static
    {
        if (!$this->specifications->contains($specification)) {
            $this->specifications->add($specification);
            $specification->setModifiedBy($this);
        }

        return $this;
    }

    public function removeSpecification(Specification $specification): static
    {
        if ($this->specifications->removeElement($specification)) {
            // set the owning side to null (unless already changed)
            if ($specification->getModifiedBy() === $this) {
                $specification->setModifiedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Type>
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function addType(Type $type): static
    {
        if (!$this->types->contains($type)) {
            $this->types->add($type);
            $type->setModifiedBy($this);
        }

        return $this;
    }

    public function removeType(Type $type): static
    {
        if ($this->types->removeElement($type)) {
            // set the owning side to null (unless already changed)
            if ($type->getModifiedBy() === $this) {
                $type->setModifiedBy(null);
            }
        }

        return $this;
    }
}
