<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\LodgingValueRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LodgingValueRepository::class)]
#[ApiResource(
  collectionOperations: ['get', 'post'],
  itemOperations: ['get', 'patch', 'delete'],
  normalizationContext: ['groups' => ['lodgingvalue:read']],
  denormalizationContext: ['groups' => ['lodgingvalue:write']],
)]
class LodgingValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Assert\Length([
      'max' => 10,
      'maxMessage' => 'Your value cannot be longer than {{ limit }} characters',
    ])]
    #[Assert\Regex(['pattern' => "/^([A-Za-z]+)$/"])]
    #[ORM\Column(type: 'string', length: 10)]
    #[Groups(["lodgingvalue:read", "lodgingvalue:write"])]
    private $value;

    #[ORM\ManyToOne(targetEntity: Lodging::class, inversedBy: 'lodgingValues')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["lodgingvalue:read"])]
    private $lodging;

    #[ORM\ManyToOne(targetEntity: Property::class, inversedBy: 'lodgingValues')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["lodgingvalue:read"])]
    private $property;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

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

    public function getProperty(): ?Property
    {
        return $this->property;
    }

    public function setProperty(?Property $property): self
    {
        $this->property = $property;

        return $this;
    }
}
