<?php

namespace App\Entity;

use App\Repository\ActivityCodeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityCodeRepository::class)]
class ActivityCode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $activityCode = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'activityCodes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $modifiedBy = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActivityCode(): ?string
    {
        return $this->activityCode;
    }

    public function setActivityCode(string $activityCode): static
    {
        $this->activityCode = $activityCode;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getModifiedBy(): ?User
    {
        return $this->modifiedBy;
    }

    public function setModifiedBy(?User $modifiedBy): static
    {
        $this->modifiedBy = $modifiedBy;

        return $this;
    }
}
