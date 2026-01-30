<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
class Booking
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['client:read', 'booking:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Activity::class, inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['client:read', 'booking:read'])]
    private ?Activity $activity = null;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(?Activity $activity): static
    {
        $this->activity = $activity;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    #[Groups(['booking:read'])]
    public function getClientId(): ?int {
        return $this->client?->getId();
    }
}