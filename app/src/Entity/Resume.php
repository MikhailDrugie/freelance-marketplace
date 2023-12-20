<?php

namespace App\Entity;

use App\Repository\ResumeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: ResumeRepository::class)]
#[ORM\Table(name: 'resumes', schema: 'public')]
class Resume extends BaseEntity
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
    private ?int $freelancer_id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?int $experience = null;

    #[ORM\Column(type: Types::JSON)]
    private array $tags = [];

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::JSON)]
    private array $contact_form = [];

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\ManyToOne(targetEntity: Freelancer::class, inversedBy: 'resumes')]
    #[ORM\JoinColumn(name: 'freelancer_id', referencedColumnName: 'id')]
    private ?Freelancer $author = null;

    /** @var ?array<Chat> $chats */
    #[ORM\OneToMany(mappedBy: 'resume', targetEntity: ResumeChat::class)]
    private ?array $chats = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFreelancerId(): ?int
    {
        return $this->freelancer_id;
    }

    public function setFreelancerId(int $freelancer_id): static
    {
        $this->freelancer_id = $freelancer_id;

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

    public function getExperience(): ?int
    {
        return $this->experience;
    }

    public function setExperience(int $experience): static
    {
        $this->experience = $experience;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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

    public function getAuthor(): ?Freelancer
    {
        return $this->author;
    }

    public function getChats(): ?array
    {
        return $this->chats;
    }
}
