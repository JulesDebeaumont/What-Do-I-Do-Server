<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
#[ApiResource(
    iri: "http://schema.org/User",
    collectionOperations: [
        'post' => [ 
            'denormalization_context' => ['groups' => ['user_create']],
            'normalization_context' => ['groups' => ['user_read']]
        ]
    ],
    itemOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['user_read']],
            "security" => "is_granted('ROLE_USER') and object == user",
            "security_message" => "You can't do that!"
        ],
        'tasks' => [
            'method' => 'GET',
            'path' => '/users/{id}/tasks',
            'requirements' => ['id' => '\d+'],
            'defaults' => ['color' => 'brown'],
            'normalization_context' => ['groups' => ['user_tasks']],
            "security" => "is_granted('ROLE_USER') and object == user",
            "security_message" => "You can't do that!"
        ],
        'activites' => [
            'method' => 'GET',
            'path' => '/users/{id}/activities',
            'requirements' => ['id' => '\d+'],
            'defaults' => ['color' => 'brown'],
            'normalization_context' => ['groups' => ['user_activities']],
            "security" => "is_granted('ROLE_USER') and object == user",
            "security_message" => "You can't do that!"
        ],
        'delete' => [
            'normalization_context' => ['groups' => ['user_read']],
            "security" => "is_granted('ROLE_USER') and object == user",
            "security_message" => "You can't do that!"
        ]
    ]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(["user_read", "activity_read", "task_read", "user_tasks", "user_activities"])]
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email(message = "The email '{{ value }}' is not a valid email.")
     */
    #[Groups(["user_read", "user_create"])]
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Length(
     *      min = 8,
     *      minMessage = "Passwors must be at least {{ limit }} characters long"
     * )
     */
    #[Groups("user_create")]
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Activity::class, mappedBy="owner", orphanRemoval=true)
     */
    #[Groups("user_activities")]
    private $activities;

    /**
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="owner", orphanRemoval=true)
     */
    #[Groups("user_tasks")]
    private $tasks;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
        $this->tasks = new ArrayCollection();
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
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    

    /**
     * @return Collection|Activity[]
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): self
    {
        if (!$this->activities->contains($activity)) {
            $this->activities[] = $activity;
            $activity->setOwner($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): self
    {
        if ($this->activities->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getOwner() === $this) {
                $activity->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setOwner($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getOwner() === $this) {
                $task->setOwner(null);
            }
        }

        return $this;
    }
}
