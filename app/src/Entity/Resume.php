<?php

namespace App\Entity;

use App\Repository\ResumeRepository;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: ResumeRepository::class)]
#[ORM\Table(name: 'resumes', schema: 'public')]
#[HasLifecycleCallbacks]
class Resume extends BaseEntity
{
    const EXP_NOT_MATTER = null;
    const EXP_NONE = 1;
    const EXP_LESS_THAN_ONE = 2;
    const EXP_ONE_TO_THREE = 3;
    const EXP_THREE_TO_FIVE = 4;
    const EXP_SIX_AND_MORE = 5;
    const EXP_TEXT_MAP = [
        'Не указано' => self::EXP_NOT_MATTER,
        'Без опыта' => self::EXP_NONE,
        'Меньше года' => self::EXP_LESS_THAN_ONE,
        'От 1 до 3 лет' => self::EXP_ONE_TO_THREE,
        'От 3 до 5 лет' => self::EXP_THREE_TO_FIVE,
        'От 6 лет' => self::EXP_SIX_AND_MORE
    ];

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_DELETED = 3;


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

    /** @var ?Collection<Chat> $chats */
    #[ORM\OneToMany(mappedBy: 'resume', targetEntity: ResumeChat::class)]
    private ?Collection $chats = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    private ?string $tagsText = null;

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

    public function getExperienceText(): string
    {
        return match ($this->experience) {
            self::EXP_NONE => 'Без опыта',
            self::EXP_LESS_THAN_ONE => 'Меньше года',
            self::EXP_ONE_TO_THREE => 'От 1 до 3 лет',
            self::EXP_THREE_TO_FIVE => 'От 3 до 5 лет',
            self::EXP_SIX_AND_MORE => 'От 6 лет',
            self::EXP_NOT_MATTER => 'Не указано',
            default => 'Неизвестно'
        };
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

    public function getTagsText(): string
    {
        return implode(', ', $this->tags);
    }

    public function setTagsText(string $text): static
    {
        $this->tags = explode(',', $text);
        return $this;
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

    public function getStatusText(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => 'Показывается',
            self::STATUS_INACTIVE => 'Не показывается',
            default => 'Неизвестно',
        };
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

    public function getAuthor(): ?Freelancer
    {
        return $this->author;
    }

    public function setAuthor(Freelancer $author): static
    {
        $this->author = $author;
        return $this;
    }

    public function getChats(): ?Collection
    {
        return $this->chats;
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
