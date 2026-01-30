<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: \App\Repository\ActivityRepository::class)]
class Activity
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['activity:read', 'client:read', 'booking:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['activity:read'])]
    private ?int $max_participants = null;

    #[ORM\Column(length: 50)] // BodyPump, Spinning, Core
    #[Groups(['activity:read', 'client:read'])]
    private ?string $type = null;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['activity:read', 'client:read'])]
    private ?\DateTimeInterface $date_start = null;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['activity:read', 'client:read'])]
    private ?\DateTimeInterface $date_end = null;

    #[ORM\OneToMany(mappedBy: 'activity', targetEntity: Song::class, cascade: ['persist'])]
    #[Groups(['activity:read'])]
    #[SerializedName('play_list')]
    private Collection $playList;

    #[ORM\OneToMany(mappedBy: 'activity', targetEntity: Booking::class)]
    private Collection $bookings;

    public function __construct() {
        $this->playList = new ArrayCollection();
        $this->bookings = new ArrayCollection();
    }

    #[Groups(['activity:read'])]
    #[SerializedName('clients_signed')]
    public function getClientsSigned(): int {
        return $this->bookings->count();
    }

    public function getMaxParticipants(): ?int
    {
        return $this->max_participants;
    }

    public function setMaxParticipants(int $max_participants): static
    {
        $this->max_participants = $max_participants;

        return $this;
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

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->date_start;
    }

    public function setDateStart(\DateTimeInterface $date_start): static
    {
        $this->date_start = $date_start;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->date_end;
    }

    public function setDateEnd(\DateTimeInterface $date_end): static
    {
        $this->date_end = $date_end;

        return $this;
    }

    /**
     * @return Collection<int, Song>
     */
    public function getPlayList(): Collection
    {
        return $this->playList;
    }

    public function addPlayList(Song $playList): static
    {
        if (!$this->playList->contains($playList)) {
            $this->playList->add($playList);
            $playList->setActivity($this);
        }

        return $this;
    }

    public function removePlayList(Song $playList): static
    {
        if ($this->playList->removeElement($playList)) {
            // set the owning side to null (unless already changed)
            if ($playList->getActivity() === $this) {
                $playList->setActivity(null);
            }
        }

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
            $booking->setActivity($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getActivity() === $this) {
                $booking->setActivity(null);
            }
        }

        return $this;
    }
}