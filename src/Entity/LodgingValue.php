<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LodgingValueRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LodgingValueRepository::class)]
#[ApiResource]
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
    private $value;

    #[ORM\ManyToOne(targetEntity: Lodging::class, inversedBy: 'lodgingValues')]
    #[ORM\JoinColumn(nullable: false)]
    private $lodging;

    #[ORM\ManyToOne(targetEntity: Property::class, inversedBy: 'lodgingValues')]
    #[ORM\JoinColumn(nullable: false)]
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
