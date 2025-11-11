<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titulo = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $sinopsis = null;

    #[ORM\Column(length: 100)]
    private ?string $genero = null;

    #[ORM\Column]
    private ?int $anio = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $director = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $poster = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $duracion = null; // en minutos

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'movie', orphanRemoval: true)]
    private Collection $reviews;

    #[ORM\OneToMany(targetEntity: Recommendation::class, mappedBy: 'movie', orphanRemoval: true)]
    private Collection $recommendations;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'viewedMovies')]
    private Collection $viewedByUsers;

    #[ORM\ManyToMany(targetEntity: Group::class, inversedBy: 'movies')]
    private Collection $groups;

    #[ORM\OneToMany(targetEntity: GroupRecommendation::class, mappedBy: 'movie', orphanRemoval: true)]
    private Collection $groupRecommendations;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->recommendations = new ArrayCollection();
        $this->viewedByUsers = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->groupRecommendations = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;
        return $this;
    }

    public function getSinopsis(): ?string
    {
        return $this->sinopsis;
    }

    public function setSinopsis(string $sinopsis): static
    {
        $this->sinopsis = $sinopsis;
        return $this;
    }

    public function getGenero(): ?string
    {
        return $this->genero;
    }

    public function setGenero(string $genero): static
    {
        $this->genero = $genero;
        return $this;
    }

    public function getAnio(): ?int
    {
        return $this->anio;
    }

    public function setAnio(int $anio): static
    {
        $this->anio = $anio;
        return $this;
    }

    public function getDirector(): ?string
    {
        return $this->director;
    }

    public function setDirector(?string $director): static
    {
        $this->director = $director;
        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(?string $poster): static
    {
        $this->poster = $poster;
        return $this;
    }

    public function getDuracion(): ?int
    {
        return $this->duracion;
    }

    public function setDuracion(?int $duracion): static
    {
        $this->duracion = $duracion;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setMovie($this);
        }
        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            if ($review->getMovie() === $this) {
                $review->setMovie(null);
            }
        }
        return $this;
    }

    public function getAverageRating(): ?float
    {
        if ($this->reviews->isEmpty()) {
            return null;
        }

        $total = 0;
        foreach ($this->reviews as $review) {
            $total += $review->getPuntuacion();
        }

        return round($total / $this->reviews->count(), 1);
    }

    public function getViewedByUsers(): Collection
    {
        return $this->viewedByUsers;
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

    public function getGroupRecommendations(): Collection
    {
        return $this->groupRecommendations;
    }
}