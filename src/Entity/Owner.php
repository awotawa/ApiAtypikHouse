<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OwnerRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OwnerRepository::class)]
#[ApiResource(
  collectionOperations: [
    'get',
    'post' => ['security' => 'is_granted("ROLE_ADMIN")']
  ],
  itemOperations: [
    'get',
    'patch' => ['security' => 'is_granted("ROLE_ADMIN")'],
    'delete' => ['security' => 'is_granted("ROLE_ADMIN")']
  ],
  normalizationContext: ['groups' => ['owner:read']],
  denormalizationContext: ['groups' => ['owner:write']],
)]
class Owner
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToOne(inversedBy: 'owner', targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["owner:read", "owner:write"])]
    private $user;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Lodging::class, orphanRemoval: true)]
    #[Groups(["owner:read"])]
    private $lodgings;

    public function __construct()
    {
        $this->lodgings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Lodging>
     */
    public function getLodgings(): Collection
    {
        return $this->lodgings;
    }

    public function addLodging(Lodging $lodging): self
    {
        if (!$this->lodgings->contains($lodging)) {
            $this->lodgings[] = $lodging;
            $lodging->setOwner($this);
        }

        return $this;
    }

    public function removeLodging(Lodging $lodging): self
    {
        if ($this->lodgings->removeElement($lodging)) {
            // set the owning side to null (unless already changed)
            if ($lodging->getOwner() === $this) {
                $lodging->setOwner(null);
            }
        }

        return $this;
    }
}
