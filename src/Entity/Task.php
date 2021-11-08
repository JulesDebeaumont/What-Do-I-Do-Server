<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 */
#[ApiResource(
    iri: "http://schema.org/Task",
    attributes: [
        "security" => "is_granted('ROLE_USER')",
        "security_message" => "You need to be logged in to do that!"
    ],
    collectionOperations: [
        'post' => [
            "security" => "is_granted('ROLE_USER')",
            "security_message" => "You need to be logged in to do that!"
        ]
    ],
    itemOperations: [
        'get' => [
            "security" => "is_granted('ROLE_USER') and object.owner == user",
            "security_message" => "You don't own this!"
        ],
        'put' => [
            "security" => "is_granted('ROLE_USER') and object.owner == user",
            "security_message" => "You don't own this!"
        ],
        'delete' => [
            "security" => "is_granted('ROLE_USER') and object.owner == user",
            "security_message" => "You don't own this!"
        ]
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'partial'])]
class Task
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActivated;

    /**
     * @ORM\Column(type="integer")
     */
    private $repeatInterval;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

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

    public function getIsActivated(): ?bool
    {
        return $this->isActivated;
    }

    public function setIsActivated(bool $isActivated): self
    {
        $this->isActivated = $isActivated;

        return $this;
    }

    public function getRepeatInterval(): ?int
    {
        return $this->repeatInterval;
    }

    public function setRepeatInterval(int $repeatInterval): self
    {
        $this->repeatInterval = $repeatInterval;

        return $this;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
