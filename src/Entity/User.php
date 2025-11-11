<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'Ya existe una cuenta con este correo electrónico')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 100)]
    private ?string $nombre = null;

    #[ORM\Column(length: 100)]
    private ?string $apellido = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $fechaNacimiento = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $reviews;

    #[ORM\OneToMany(targetEntity: Recommendation::class, mappedBy: 'fromUser', orphanRemoval: true)]
    private Collection $sentRecommendations;

    #[ORM\OneToMany(targetEntity: Recommendation::class, mappedBy: 'toUser', orphanRemoval: true)]
    private Collection $receivedRecommendations;

    #[ORM\ManyToMany(targetEntity: Group::class, inversedBy: 'members')]
    #[ORM\JoinTable(name: 'group_membership')]
    private Collection $groups;

    #[ORM\ManyToMany(targetEntity: Movie::class, inversedBy: 'viewedByUsers')]
    #[ORM\JoinTable(name: 'movie_viewed')]
    private Collection $viewedMovies;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->sentRecommendations = new ArrayCollection();
        $this->receivedRecommendations = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->viewedMovies = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->roles = ['ROLE_USER'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // Limpiar datos temporales sensibles
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): static
    {
        $this->apellido = $apellido;
        return $this;
    }

    public function getFechaNacimiento(): ?\DateTimeInterface
    {
        return $this->fechaNacimiento;
    }

    public function setFechaNacimiento(\DateTimeInterface $fechaNacimiento): static
    {
        $this->fechaNacimiento = $fechaNacimiento;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setUser($this);
        }
        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            if ($review->getUser() === $this) {
                $review->setUser(null);
            }
        }
        return $this;
    }

    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): static
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
        }
        return $this;
    }

    public function removeGroup(Group $group): static
    {
        $this->groups->removeElement($group);
        return $this;
    }

    public function getViewedMovies(): Collection
    {
        return $this->viewedMovies;
    }

    public function addViewedMovie(Movie $movie): static
    {
        if (!$this->viewedMovies->contains($movie)) {
            $this->viewedMovies->add($movie);
        }
        return $this;
    }

    public function removeViewedMovie(Movie $movie): static
    {
        $this->viewedMovies->removeElement($movie);
        return $this;
    }

    public function hasViewedMovie(Movie $movie): bool
    {
        return $this->viewedMovies->contains($movie);
    }

    public function getNombreCompleto(): string
    {
        return $this->nombre . ' ' . $this->apellido;
    }
}