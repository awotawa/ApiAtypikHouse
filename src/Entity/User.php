<?php

namespace App\Entity;

// use ApiPlatform\Core\Annotation\ApiFilter;
// use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\MeController;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use ApiPlatform\Core\Annotation\ApiSubresource;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
//   security: 'is_granted("ROLE_USER")',
  collectionOperations: [
      'me' => [
        'security' => 'is_granted("ROLE_USER")',
        'pagination_enabled' => false,
        'path' => '/users/me',
        'method' => 'get',
        'controller' => MeController::class,
        'read' => false,
        'openapi_context' => [
          'security' => [['bearerAuth' => []]],
        ]
      ],
      'get',
      'post' => [
        'security' => 'is_granted("PUBLIC_ACCESS")',
      ]
    ],
  itemOperations: [
    'get' => [
      'controller' => NotFoundAction::class,
      'openapi_context' => ['summary' => 'hidden'],
      'read' => false,
      'output' => false
    ],
    'patch',
    'delete'
  ],
  normalizationContext: ['groups' => ['user:read']],
  denormalizationContext: ['groups' => ['user:write']],
)]
// #[ApiFilter(SearchFilter::class, properties: [
//     'owner.id' => 'exact'
//     ]
// )]
class User implements UserInterface, JWTUserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["user:read"])]
    private $id;

    #[Assert\NotBlank()]
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\Email()]
    #[Groups(["user:read", "user:write", "owner:read", "reservation:read"])]
    private $email;

    #[ORM\Column(type: 'json')]
    #[Groups(["user:read", "user:write"])]
    private $roles = [];

    #[Assert\NotBlank()]
    #[Assert\Length([
      'min' => 8,
      'minMessage' => 'Your password must be at least {{ limit }} characters long',
    ])]
    #[Assert\Regex(['pattern' => "/^(?=.*[A-Z])(?=.*[\-._~!@#$&*])(?=.*[0-9])(?=.*[a-z]).{8,}$/"])]
    #[ORM\Column(type: 'string')]
    #[Groups(["user:write"])]
    private $password;

    #[Assert\NotBlank()]
    #[Assert\Length([
      'min' => 2,
      'max' => 255,
      'minMessage' => 'Your first name must be at least {{ limit }} characters long',
      'maxMessage' => 'Your first name cannot be longer than {{ limit }} characters',
    ])]
    #[Assert\Regex(['pattern' => "/^([A-Za-zÀ-ÿ]+)$/"])]
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["user:read", "user:write", "owner:read"])]
    private $firstName;

    #[Assert\NotBlank()]
    #[Assert\Length([
      'min' => 2,
      'max' => 255,
      'minMessage' => 'Your last name must be at least {{ limit }} characters long',
      'maxMessage' => 'Your last name cannot be longer than {{ limit }} characters',
    ])]
    #[Assert\Regex(['pattern' => "/^([A-Za-zÀ-ÿ]+)$/"])]
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["user:read", "user:write", "owner:read"])]
    private $lastName;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Regex(['pattern' => "/(https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&=]*))/"])]
    #[Groups(["user:read", "user:write", "owner:read"])]
    private $photo;

    #[ApiSubresource()]
    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Owner::class, cascade: ['persist', 'remove'])]
    #[Groups(["user:read"])]
    private $owner;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Reservation::class, orphanRemoval: true)]
    #[Groups(["user:read"])]
    private $reservations;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ApiToken::class, orphanRemoval: true)]
    private $apiTokens;

    #[ORM\OneToMany(mappedBy: 'sender', targetEntity: Message::class, orphanRemoval: true)]
    private $messages;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->apiTokens = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setId(?int $id): self
    {
      $this->id = $id;

      return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getOwner(): ?Owner
    {
        return $this->owner;
    }

    public function setOwner(Owner $owner): self
    {
        // set the owning side of the relation if necessary
        if ($owner->getUser() !== $this) {
            $owner->setUser($this);
        }

        $this->owner = $owner;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserName(): string
    {
      return (string) $this->email;
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
            $reservation->setUser($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getUser() === $this) {
                $reservation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ApiToken>
     */
    public function getApiTokens(): Collection
    {
        return $this->apiTokens;
    }

    public function addApiToken(ApiToken $apiToken): self
    {
        if (!$this->apiTokens->contains($apiToken)) {
            $this->apiTokens[] = $apiToken;
            $apiToken->setUser($this);
        }

        return $this;
    }

    public function removeApiToken(ApiToken $apiToken): self
    {
        if ($this->apiTokens->removeElement($apiToken)) {
            // set the owning side to null (unless already changed)
            if ($apiToken->getUser() === $this) {
                $apiToken->setUser(null);
            }
        }

        return $this;
    }

    public static function createFromPayload($id, array $payload)
    {
      return (new User())
        ->setId($id)
        ->setEmail($payload['username'] ?? '')
        ->setRoles($payload['roles'] ?? '')
        ->setFirstName($payload['firstname'] ?? '')
        ->setLastName($payload['lastname'] ?? '')
        ->setPhoto($payload['photo'] ?? '');
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setSender($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getSender() === $this) {
                $message->setSender(null);
            }
        }

        return $this;
    }

}
