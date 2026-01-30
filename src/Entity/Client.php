<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: \App\Repository\ClientRepository::class)]
class Client
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['client:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 20)] // standard, premium
    #[Groups(['client:read'])]
    private ?string $type = 'standard';

    #[ORM\Column(length: 255)]
    #[Groups(['client:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['client:read'])]
    private ?string $email = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Booking::class)]
    #[Groups(['client:read_bookings'])] // Controlamos con grupos si enviamos las reservas
    #[SerializedName('activities_booked')]
    private Collection $bookings;

    #[Groups(['client:read'])]
    private ?array $activity_statistics = null;

    public function getActivityStatistics(): ?array
    {
        return $this->activity_statistics;
    }

    public function setActivityStatistics(?array $activity_statistics): self
    {
        $this->activity_statistics = $activity_statistics;
        return $this;
    }

    public function __construct() {
        $this->bookings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
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
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): static
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings->add($booking);
            $booking->setClient($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getClient() === $this) {
                $booking->setClient(null);
            }
        }

        return $this;
    }
}