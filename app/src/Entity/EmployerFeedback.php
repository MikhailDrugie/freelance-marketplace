<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class EmployerFeedback extends Feedback
{
    const RECIPIENT_CLASS = Employer::class;
    const AUTHOR_CLASS = Freelancer::class;

    #[ORM\ManyToOne(targetEntity: self::RECIPIENT_CLASS, inversedBy: 'receivedFeedbacks')]
    #[ORM\JoinColumn(name: 'recipient_id', referencedColumnName: 'id')]
    private ?Employer $recipient = null;

    #[ORM\ManyToOne(targetEntity: self::AUTHOR_CLASS, inversedBy: 'postedFeedbacks')]
    #[ORM\JoinColumn(name: 'author_id', referencedColumnName: 'id')]
    private ?Freelancer $author = null;
}