<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ORM\Table(name: 'projects', schema: 'public')]
class Project
{
    const EXP_NOT_MATTER = null;
    const EXP_NONE = 1;
    const EXP_LESS_THAN_ONE = 2;
    const EXP_ONE_TO_THREE = 3;
    const EXP_THREE_TO_FIVE = 4;
    const EXP_SIX_AND_MORE = 5;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $employer_id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(nullable: true)]
    private ?int $required_experience = null;

    #[ORM\Column(type: Types::JSON)]
    private array $tags = [];

    #[ORM\Column(type: Types::JSON)]
    private array $contact_form = [];

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\ManyToOne(targetEntity: Employer::class, inversedBy: 'projects')]
    #[ORM\JoinColumn(name: 'employer_id', referencedColumnName: 'id')]
    private ?Employer $author = null;

    /** @var ?array<Chat> $chats */
    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Chat::class)]
    private ?array $chats = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployerId(): ?int
    {
        return $this->employer_id;
    }

    public function setEmployerId(int $employer_id): static
    {
        $this->employer_id = $employer_id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getRequiredExperience(): ?int
    {
        return $this->required_experience;
    }

    public function setRequiredExperience(?int $required_experience): static
    {
        $this->required_experience = $required_experience;

        return $this;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setTags(array $tags): static
    {
        $this->tags = $tags;

        return $this;
    }

    public function getContactForm(): array
    {
        return $this->contact_form;
    }

    public function setContactForm(array $contact_form): static
    {
        $this->contact_form = $contact_form;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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

    public function getAuthor(): ?Employer
    {
        return $this->author;
    }

    public function getChats(): ?array
    {
        return $this->chats;
    }
}
