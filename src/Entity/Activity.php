<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=ActivityRepository::class)
 */
#[ApiResource(
    iri: "http://schema.org/Activity",
    attributes: [
        "security" => "is_granted('ROLE_USER')",
        "security_message" => "You need to be logged in to do that!"
    ],
    collectionOperations: [
        'get' => [
            "security" => "is_granted('ROLE_USER') and object.owner == user",
            "security_message" => "You don't own this!"
        ],
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
class Activity
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
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="activities")
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

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

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
