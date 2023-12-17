<?php

namespace App\Entity;

use App\Repository\FreelancerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: FreelancerRepository::class)]
#[ORM\Table(name: 'freelancers', schema: 'public')]
class Freelancer
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

    #[ORM\OneToOne(inversedBy: 'freelancer', targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private ?User $user = null;

    /* @var array<Resume>|null $resumes */
    #[ORM\OneToMany(mappedBy: 'freelancer', targetEntity: Resume::class)]
    #[ORM\JoinColumn(name: 'id', referencedColumnName: 'freelancer_id')]
    private ?array $resumes = [];

    /** @var array<FreelancerFeedback> $receivedFeedbacks */
    #[ORM\OneToMany(mappedBy: 'recipient', targetEntity: FreelancerFeedback::class)]
    private ?array $receivedFeedbacks = null;

    /** @var array<EmployerFeedback> $postedFeedbacks */
    #[ORM\OneToMany(mappedBy: 'author', targetEntity: EmployerFeedback::class)]
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

    public function getResumes(): ?array
    {
        return $this->resumes;
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
