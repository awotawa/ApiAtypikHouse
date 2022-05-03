<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PropertyRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PropertyRepository::class)]
#[ApiResource(
  collectionOperations: ['get', 'post'],
  itemOperations: ['get', 'patch', 'delete'],
  normalizationContext: ['groups' => ['property:read']],
  denormalizationContext: ['groups' => ['property:write']],
)]
class Property
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Assert\Length([
      'max' => 30,
      'maxMessage' => 'Your newField cannot be longer than {{ limit }} characters',
    ])]
    #[Assert\Regex(['pattern' => "/^([A-Za-zÀ-ÿ '-]+)$/"])]
    #[ORM\Column(type: 'string', length: 30)]
    #[Groups(["property:read", "property:write", "lodgingvalue:read"])]
    private $newField;

    #[Assert\Length([
      'max' => 30,
      'maxMessage' => 'Your defaultValue cannot be longer than {{ limit }} characters',
    ])]
    #[Assert\Regex(['pattern' => "/^([A-Za-zÀ-ÿ0-9 '²,.-]+)$/"])]
    #[ORM\Column(type: 'string', length: 30)]
    #[Groups(["property:read", "property:write", "lodgingvalue:read"])]
    private $defaultValue;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'properties')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["property:read", "property:write"])]
    private $category;

    #[ORM\OneToMany(mappedBy: 'property', targetEntity: LodgingValue::class, orphanRemoval: true)]
    #[Groups(["property:read"])]
    private $lodgingValues;

    public function __construct()
    {
        $this->lodgingValues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNewField(): ?string
    {
        return $this->newField;
    }

    public function setNewField(string $newField): self
    {
        $this->newField = $newField;

        return $this;
    }

    public function getDefaultValue(): ?string
    {
        return $this->defaultValue;
    }

    public function setDefaultValue(string $defaultValue): self
    {
        $this->defaultValue = $defaultValue;

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
            $lodgingValue->setProperty($this);
        }

        return $this;
    }

    public function removeLodgingValue(LodgingValue $lodgingValue): self
    {
        if ($this->lodgingValues->removeElement($lodgingValue)) {
            // set the owning side to null (unless already changed)
            if ($lodgingValue->getProperty() === $this) {
                $lodgingValue->setProperty(null);
            }
        }

        return $this;
    }
}
