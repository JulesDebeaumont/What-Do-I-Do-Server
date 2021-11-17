<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ActivityRepository::class)
 */
#[ApiResource(
    iri: "http://schema.org/Activity",
    normalizationContext: ['groups' => ['activity_read']],
    attributes: [
        "security" => "is_granted('ROLE_USER')",
        "security_message" => "You need to be logged in to do that!",
        "openapi_context" => ['security' => [['bearerAuth' => []]]]
    ],
    collectionOperations: [
        'post' => [
            "security" => "is_granted('ROLE_USER')",
            "security_message" => "You need to be logged in to do that!",
            "openapi_context" => ['security' => [['bearerAuth' => []]]],
            "denormalization_context" => ['groups' => ['activity_post']]
        ]
    ],
    itemOperations: [
        'get' => [
            "security" => "is_granted('ROLE_USER') and object.getOwner().getId() == user.getId()",
            "security_message" => "You don't own this!",
            "openapi_context" => ['security' => [['bearerAuth' => []]]]
        ],
        'patch' => [
            "security" => "is_granted('ROLE_USER') and object.getOwner().getId() == user.getId()",
            "security_message" => "You don't own this!",
            "openapi_context" => ['security' => [['bearerAuth' => []]]],
            "denormalization_context" => ['groups' => ['activity_write']]
        ],
        'delete' => [
            "security" => "is_granted('ROLE_USER') and object.getOwner().getId() == user.getId()",
            "security_message" => "You don't own this!",
            "openapi_context" => ['security' => [['bearerAuth' => []]]]
        ]
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'partial'])]
class Activity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(["activity_read", "user_activities"])]
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["activity_read", "activity_write", "user_activities", "activity_post"])]
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    #[Groups(["activity_read", "activity_write", "user_activities", "activity_post"])]
    private $duration;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="activities")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups(["activity_read", "activity_post"])]
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
