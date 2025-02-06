<?php

namespace App\Entity;

use App\Repository\UserInfoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserInfoRepository::class)]
class UserInfo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $fn = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mn = null;

    #[ORM\Column(length: 255)]
    private ?string $ln = null;

    #[ORM\Column(length: 255)]
    private ?string $office = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $division = null;

    #[ORM\Column(length: 255)]
    private ?string $position = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    // #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    // #[ORM\JoinColumn(nullable: false)]
    // private ?User $user = null;

    #[ORM\OneToOne(targetEntity: User::class, inversedBy: "userInfo", cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFn(): ?string
    {
        return $this->fn;
    }

    public function setFn(string $fn): static
    {
        $this->fn = $fn;

        return $this;
    }

    public function getMn(): ?string
    {
        return $this->mn;
    }

    public function setMn(?string $mn): static
    {
        $this->mn = $mn;

        return $this;
    }

    public function getLn(): ?string
    {
        return $this->ln;
    }

    public function setLn(string $ln): static
    {
        $this->ln = $ln;

        return $this;
    }

    public function getName(): string
    {
        // return $this->fn . ' ' . $this->mn . ' ' . $this->ln;

        $name = $this->fn;
        
        if ($this->mn) {
            $name .= ' ' . $this->mn;
        }
        
        if ($this->ln) {
            $name .= ' ' . $this->ln;
        }
        
        return $name;
    }

    public function getOffice(): ?string
    {
        return $this->office;
    }

    public function setOffice(string $office): static
    {
        $this->office = $office;

        return $this;
    }

    public function getDivision(): ?string
    {
        return $this->division;
    }

    public function setDivision(?string $division): static
    {
        $this->division = $division;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }
}
