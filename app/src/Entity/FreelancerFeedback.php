<?php

namespace App\Entity;

use App\Repository\FeedbackRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class FreelancerFeedback extends Feedback
{
    const RECIPIENT_CLASS = Freelancer::class;
    const AUTHOR_CLASS = Employer::class;

    #[ORM\ManyToOne(targetEntity: self::RECIPIENT_CLASS, inversedBy: 'receivedFeedbacks')]
    #[ORM\JoinColumn(name: 'recipient_id', referencedColumnName: 'id')]
    private ?Freelancer $recipient = null;

    #[ORM\ManyToOne(targetEntity: self::AUTHOR_CLASS, inversedBy: 'postedFeedbacks')]
    #[ORM\JoinColumn(name: 'author_id', referencedColumnName: 'id')]
    private ?Employer $author = null;
}