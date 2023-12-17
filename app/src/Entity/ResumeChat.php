<?php

namespace App\Entity;

use App\Repository\ChatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChatRepository::class)]
class ResumeChat extends Chat
{
    #[ORM\ManyToOne(targetEntity: Resume::class, inversedBy: 'chats')]
    #[ORM\JoinColumn(name: 'linked_to_id', referencedColumnName: 'id')]
    private ?Resume $resume = null;

    public function getResume(): ?Resume
    {
        return $this->resume;
    }
}