<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
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
  normalizationContext: ['groups' => ['category:read']],
  denormalizationContext: ['groups' => ['category:write']],
)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["category:read"])]
    private $id;

    #[Assert\Length([
      'max' => 30,
      'maxMessage' => 'Your type cannot be longer than {{ limit }} characters',
    ])]
    #[Assert\NotBlank()]
    #[Assert\Regex(['pattern' => "/^([A-Za-zÀ-ÿ '-]+)$/"])]
    #[ORM\Column(type: 'string', length: 30)]
    #[Groups(["category:read", "category:write", "lodging:read"])]
    private $type;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Property::class, orphanRemoval: true)]
    #[Groups(["category:read"])]
    private $properties;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Lodging::class, orphanRemoval: true)]
    #[Groups(["category:read"])]
    #[ApiSubresource()]
    private $lodgings;

    public function __construct()
    {
        $this->properties = new ArrayCollection();
        $this->lodgings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Property>
     */
    public function getProperties(): Collection
    {
        return $this->properties;
    }

    public function addProperty(Property $property): self
    {
        if (!$this->properties->contains($property)) {
            $this->properties[] = $property;
            $property->setCategory($this);
        }

        return $this;
    }

    public function removeProperty(Property $property): self
    {
        if ($this->properties->removeElement($property)) {
            // set the owning side to null (unless already changed)
            if ($property->getCategory() === $this) {
                $property->setCategory(null);
            }
        }

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
            $lodging->setCategory($this);
        }

        return $this;
    }

    public function removeLodging(Lodging $lodging): self
    {
        if ($this->lodgings->removeElement($lodging)) {
            // set the owning side to null (unless already changed)
            if ($lodging->getCategory() === $this) {
                $lodging->setCategory(null);
            }
        }

        return $this;
    }
}
