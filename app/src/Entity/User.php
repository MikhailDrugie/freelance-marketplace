<?php

namespace App\Entity;

use App\Repository\UserGroupRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[HasLifecycleCallbacks]
#[ORM\Table(name: 'users', schema: 'public')]
#[UniqueEntity(fields: ['login'], message: 'There is already an account with this login')]
class User extends BaseEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private string $login;

    #[ORM\Column(length: 255, unique: true)]
    private string $email;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $full_name = null;

    #[ORM\Column(name: 'status')]
    private int $status = self::STATUS_ACTIVE;

    #[ORM\Column(name: 'created_at', type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at;

    #[ORM\Column(name: 'updated_at', type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updated_at;

    /**
     * @var string $password The hashed password
     */
    #[ORM\Column]
    private string $password;

    #[ORM\ManyToOne(targetEntity: UserGroup::class)]
    private UserGroup $userGroup;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Freelancer::class)]
    private ?Freelancer $freelancer = null;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Employer::class)]
    private ?Employer $employer = null;

    protected UserGroupRepository $userGroupRepository;

    public function __construct(EntityManagerInterface $entityManager) {
        parent::__construct($entityManager);
        /** @var $repository UserGroupRepository */
        $repository = $entityManager->getRepository(UserGroup::class);
        $this->userGroupRepository = $repository;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->login;
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
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    public function setFullName(?string $full_name): static
    {
        $this->full_name = $full_name;

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

    public function getUserGroup(): ?UserGroup
    {
        return $this->userGroup;
    }

    public function setUserGroup(UserGroup $userGroup): static
    {
        $this->userGroup = $userGroup;
        return $this;
    }

    #[ORM\PrePersist]
    public function setDefaultUserGroup(): void
    {
        $this->userGroup = $this->userGroupRepository->getDefaultUserGroup();
    }

    public function getFreelancer(): ?Freelancer
    {
        return $this->freelancer;
    }

    public function getEmployer(): ?Employer
    {
        return $this->employer;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getRoles(): array
    {
        $userGroup = $this->userGroup;
        return match ($userGroup->getLevel()) {
            UserGroup::LEVEL_USER => ['ROLE_USER'],
            UserGroup::LEVEL_ADMIN => ['ROLE_USER', 'ROLE_ADMIN'],
            default => [],
        };
    }

    public function getSalt(): ?string
    {
        return null;
    }
}
