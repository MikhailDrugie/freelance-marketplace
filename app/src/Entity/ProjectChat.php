<?php

namespace App\Entity;

use App\Repository\ChatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChatRepository::class)]
class ProjectChat extends Chat
{
    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'chats')]
    #[ORM\JoinColumn(name: 'linked_to_id', referencedColumnName: 'id')]
    private ?Project $project = null;

    public function getProject(): ?Project
    {
        return $this->project;
    }
}