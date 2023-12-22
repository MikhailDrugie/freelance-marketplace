<?php

namespace App\Entity;

use App\Repository\EmployerRepository;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: EmployerRepository::class)]
#[ORM\Table(name: 'employers', schema: 'public')]
#[HasLifecycleCallbacks]
class Employer extends BaseEntity
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\Column(type: Types::JSON, nullable: true, options: ['default' => '{}'])]
    private ?array $default_contact_form = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\OneToOne(inversedBy: 'employer', targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private ?User $user = null;

    /** @var ?Collection<Project> $projects */
    #[ORM\OneToMany(mappedBy: 'employer', targetEntity: Project::class)]
    #[ORM\JoinColumn(name: 'id', referencedColumnName: 'employer_id')]
    private ?Collection $projects = null;

    /** @var ?Collection<EmployerFeedback> $receivedFeedbacks */
    #[ORM\OneToMany(mappedBy: 'recipient', targetEntity: EmployerFeedback::class)]
    private ?Collection $receivedFeedbacks = null;

    /** @var ?Collection<FreelancerFeedback> $postedFeedbacks */
    #[ORM\OneToMany(mappedBy: 'author', targetEntity: FreelancerFeedback::class)]
    private ?Collection $postedFeedbacks = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getDefaultContactForm(): ?array
    {
        return $this->default_contact_form;
    }

    public function setDefaultContactForm(?array $default_contact_form): static
    {
        $this->default_contact_form = $default_contact_form;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        $this->created_at = new DateTime();
        $this->setUpdatedAt();
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updated_at = new DateTime();
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getProjects(): ?Collection
    {
        return $this->projects;
    }

    public function getReceivedFeedbacks(): ?Collection
    {
        return $this->receivedFeedbacks;
    }

    public function getPostedFeedbacks(): ?Collection
    {
        return $this->postedFeedbacks;
    }

    public function isActive(): bool
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this->status == self::STATUS_INACTIVE;
    }
}
