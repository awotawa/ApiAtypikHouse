<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReservationRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ApiResource(
  collectionOperations: ['get', 'post'],
  itemOperations: ['get', 'patch', 'delete'],
  normalizationContext: ['groups' => ['reservation:read']],
  denormalizationContext: ['groups' => ['reservation:write']],
)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'date')]
    #[Groups(["reservation:read", "reservation:write"])]
    private $startDate;

    #[ORM\Column(type: 'date')]
    #[Groups(["reservation:read", "reservation:write"])]
    private $endDate;

    #[ORM\Column(type: 'boolean')]
    #[Groups(["reservation:read", "reservation:write"])]
    private $paid;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["reservation:read", "reservation:write"])]
    private $user;

    #[ORM\ManyToOne(targetEntity: Lodging::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["reservation:read", "reservation:write"])]
    private $lodging;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getPaid(): ?bool
    {
        return $this->paid;
    }

    public function setPaid(bool $paid): self
    {
        $this->paid = $paid;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getLodging(): ?Lodging
    {
        return $this->lodging;
    }

    public function setLodging(?Lodging $lodging): self
    {
        $this->lodging = $lodging;

        return $this;
    }
}
