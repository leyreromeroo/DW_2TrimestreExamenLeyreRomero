<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
class Song
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['activity:read', 'client:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['activity:read', 'client:read'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['activity:read', 'client:read'])]
    private ?int $duration_seconds = null;

    #[ORM\ManyToOne(inversedBy: 'playList')]
    private ?Activity $activity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDurationSeconds(): ?int
    {
        return $this->duration_seconds;
    }

    public function setDurationSeconds(int $duration_seconds): static
    {
        $this->duration_seconds = $duration_seconds;

        return $this;
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
}