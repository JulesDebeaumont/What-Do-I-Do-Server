<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 */
#[ApiResource(
    iri: "http://schema.org/Task",
    normalizationContext: ['groups' => ['task_read']],
    denormalizationContext: ['groups' => ['task_write']],
    attributes: [
        "security" => "is_granted('ROLE_USER')",
        "security_message" => "You need to be logged in to do that!",
        "openapi_context" => ['security' => [['bearerAuth' => []]]]
    ],
    collectionOperations: [
        'post' => [
            "security" => "is_granted('ROLE_USER')",
            "security_message" => "You need to be logged in to do that!",
            "openapi_context" => ['security' => [['bearerAuth' => []]]]
        ]
    ],
    itemOperations: [
        'get' => [
            "security" => "is_granted('ROLE_USER') and object.getOwner() == user",
            "security_message" => "You don't own this!",
            "openapi_context" => ['security' => [['bearerAuth' => []]]]
        ],
        'patch' => [
            "security" => "is_granted('ROLE_USER') and object.getOwner() == user",
            "security_message" => "You don't own this!",
            "openapi_context" => ['security' => [['bearerAuth' => []]]]
        ],
        'delete' => [
            "security" => "is_granted('ROLE_USER') and object.getOwner() == user",
            "security_message" => "You don't own this!",
            "openapi_context" => ['security' => [['bearerAuth' => []]]]
        ]
    ]
)]
#[ApiFilter(
    SearchFilter::class,
    properties: ['name' => 'partial']
)]
class Task
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(["task_read", "user_tasks"])]
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["task_read", "task_write", "user_tasks"])]
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    #[Groups(["task_read", "task_write", "user_tasks"])]
    private $isActivated;

    /**
     * @ORM\Column(type="integer")
     */
    #[Groups(["task_read", "task_write", "user_tasks"])]
    private $repeatInterval;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Groups(["task_read", "task_write", "user_tasks"])]
    private $start;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    #[Groups(["task_read", "task_write", "user_tasks"])]
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups("task_read")]
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
