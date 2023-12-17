<?php

namespace App\Entity;

use App\Repository\EmployerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: EmployerRepository::class)]
#[ORM\Table(name: 'employers', schema: 'public')]
class Employer
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $user_id = null;

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

    /** @var ?array<Project> $projects */
    #[ORM\OneToMany(mappedBy: 'employer', targetEntity: Project::class)]
    #[ORM\JoinColumn(name: 'id', referencedColumnName: 'employer_id')]
    private ?array $projects = null;

    /** @var array<EmployerFeedback> $receivedFeedbacks */
    #[ORM\OneToMany(mappedBy: 'recipient', targetEntity: EmployerFeedback::class)]
    private ?array $receivedFeedbacks = null;

    /** @var array<FreelancerFeedback> $postedFeedbacks */
    #[ORM\OneToMany(mappedBy: 'author', targetEntity: FreelancerFeedback::class)]
    private ?array $postedFeedbacks = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
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

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getProjects(): ?array
    {
        return $this->projects;
    }

    public function getReceivedFeedbacks(): ?array
    {
        return $this->receivedFeedbacks;
    }

    public function getPostedFeedbacks(): ?array
    {
        return $this->postedFeedbacks;
    }
}
