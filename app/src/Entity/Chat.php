<?php

namespace App\Entity;

use App\Repository\ChatRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: ChatRepository::class)]
#[ORM\Table(name: 'chats', schema: 'public')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'linked_to', type: 'string')]
#[ORM\DiscriminatorMap([Chat::LINKED_TO_PROJECT => ProjectChat::class, Chat::LINKED_TO_RESUME => ResumeChat::class])]
class Chat extends BaseEntity
{
    const LINKED_TO_PROJECT = 'project';
    const LINKED_TO_RESUME = 'resume';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $freelancer_id = null;

    #[ORM\Column]
    private ?int $employer_id = null;

    private ?string $linked_to = null;

    #[ORM\Column(nullable: true)]
    private ?int $linked_to_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(options: ['default' => 1])]
    private ?int $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeInterface $updated_at = null;

    /** @var ?array<Message> $messages */
    #[ORM\OneToMany(mappedBy: 'chat', targetEntity: Message::class)]
    #[ORM\JoinColumn(name: 'id', referencedColumnName: 'chat_id')]
    private ?array $messages = null;

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

    public function getEmployerId(): ?int
    {
        return $this->employer_id;
    }

    public function setEmployerId(int $employer_id): static
    {
        $this->employer_id = $employer_id;

        return $this;
    }

    public function getLinkedTo(): ?string
    {
        return $this->linked_to;
    }

    public function setLinkedTo(?string $linked_to): static
    {
        $this->linked_to = $linked_to;

        return $this;
    }

    public function getLinkedToId(): ?int
    {
        return $this->linked_to_id;
    }

    public function setLinkedToId(?int $linked_to_id): static
    {
        $this->linked_to_id = $linked_to_id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
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

    public function getMessages(): ?array
    {
        return $this->messages;
    }
}
