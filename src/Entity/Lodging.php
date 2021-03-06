<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\LodgingRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

#[ORM\Entity(repositoryClass: LodgingRepository::class)]
#[ApiResource(
  collectionOperations: [
    'get',
    'post' => ['security' => 'is_granted("ROLE_OWNER")']
  ],
  itemOperations: [
    'get',
    'patch' => ['security' => '(is_granted("ROLE_OWNER") and object.getOwner().getUser() === user) or is_granted("ROLE_ADMIN")'],
    'delete' => ['security' => 'is_granted("ROLE_ADMIN")']
  ],
  normalizationContext: ['groups' => ['lodging:read']],
  denormalizationContext: ['groups' => ['lodging:write']],
  attributes: ["pagination_items_per_page" => 10],
)]
#[ApiFilter(SearchFilter::class, properties: [
              'address' => 'partial',
              'category.id' => 'exact'
              ]
)]
class Lodging
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["lodging:read"])]
    private $id;

    #[Assert\NotBlank()]
    #[Assert\Length([
      'min' => 2,
      'max' => 50,
      'minMessage' => 'The name must be at least {{ limit }} characters long',
      'maxMessage' => 'The name cannot be longer than {{ limit }} characters',
    ])]
    #[Assert\Regex(['pattern' => "/^([A-Za-zÀ-ÿ '-.]+)$/"])]
    #[ORM\Column(type: 'string', length: 50)]
    #[Groups(["lodging:read", "lodging:write"])]
    private $name;

    #[Assert\NotBlank()]
    #[Assert\Range([
      'min' => 0.01,
      'max' => 9999.99
    ])]
    #[Assert\Regex(['pattern' => "/^([0-9]+(\.[0-9]{0,2})?)$/"])]
    #[ORM\Column(type: 'float')]
    #[Groups(["lodging:read", "lodging:write", "reservation:read"])]
    private $rate;

    #[Assert\NotBlank()]
    #[Assert\Length([
      'min' => 50,
      'max' => 255,
      'minMessage' => 'Your description must be at least {{ limit }} characters long',
      'maxMessage' => 'Your description cannot be longer than {{ limit }} characters',
    ])]
    #[Assert\Regex(['pattern' => "/^([A-Za-z0-9À-ÿ ',:?()~&\.-]+)$/"])]
    #[ORM\Column(type: 'text', length: 255)]
    #[Groups(["lodging:read", "lodging:write"])]
    private $description;

    #[Assert\NotBlank()]
    #[Assert\Length([
      'max' => 50,
      'maxMessage' => 'Your adress cannot be longer than {{ limit }} characters',
    ])]
    #[ORM\Column(type: 'text', length: 50)]
    #[Groups(["lodging:read", "lodging:write", "reservation:read"])]
    private $address;

    #[ORM\ManyToOne(targetEntity: Owner::class, inversedBy: 'lodgings')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["lodging:read", "lodging:write"])]
    private $owner;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'lodgings')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["lodging:read", "lodging:write"])]
    private $category;

    #[ORM\OneToMany(mappedBy: 'lodging', targetEntity: Reservation::class, orphanRemoval: true)]
    #[Groups(["lodging:read"])]
    private $reservations;

    #[ORM\OneToMany(mappedBy: 'lodging', targetEntity: LodgingValue::class, orphanRemoval: true)]
    #[Groups(["lodging:read"])]
    private $lodgingValues;

    #[ORM\OneToMany(mappedBy: 'lodging', targetEntity: Media::class, orphanRemoval: true)]
    #[Groups(["lodging:read", "lodging:write"])]
    private $media;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->lodgingValues = new ArrayCollection();
        $this->media = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(float $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getOwner(): ?Owner
    {
        return $this->owner;
    }

    public function setOwner(?Owner $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setLodging($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getLodging() === $this) {
                $reservation->setLodging(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LodgingValue>
     */
    public function getLodgingValues(): Collection
    {
        return $this->lodgingValues;
    }

    public function addLodgingValue(LodgingValue $lodgingValue): self
    {
        if (!$this->lodgingValues->contains($lodgingValue)) {
            $this->lodgingValues[] = $lodgingValue;
            $lodgingValue->setLodging($this);
        }

        return $this;
    }

    public function removeLodgingValue(LodgingValue $lodgingValue): self
    {
        if ($this->lodgingValues->removeElement($lodgingValue)) {
            // set the owning side to null (unless already changed)
            if ($lodgingValue->getLodging() === $this) {
                $lodgingValue->setLodging(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Media $medium): self
    {
        if (!$this->media->contains($medium)) {
            $this->media[] = $medium;
            $medium->setLodging($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): self
    {
        if ($this->media->removeElement($medium)) {
            // set the owning side to null (unless already changed)
            if ($medium->getLodging() === $this) {
                $medium->setLodging(null);
            }
        }

        return $this;
    }
}
