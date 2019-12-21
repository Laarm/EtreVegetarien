<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MealRepository")
 */
class Meal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="text")
     */
    private $recette;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="meal")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $postedBy;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MealFavoris", mappedBy="Meal", orphanRemoval=true)
     */
    private $mealFavoris;

    public function __construct()
    {
        $this->mealFavoris = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getRecette(): ?string
    {
        return $this->recette;
    }

    public function setRecette(string $recette): self
    {
        $this->recette = $recette;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPostedBy(): ?User
    {
        return $this->postedBy;
    }

    public function setPostedBy(?User $postedBy): self
    {
        $this->postedBy = $postedBy;

        return $this;
    }

    /**
     * @return Collection|MealFavoris[]
     */
    public function getMealFavoris(): Collection
    {
        return $this->mealFavoris;
    }

    public function addMealFavori(MealFavoris $mealFavori): self
    {
        if (!$this->mealFavoris->contains($mealFavori)) {
            $this->mealFavoris[] = $mealFavori;
            $mealFavori->setMeal($this);
        }

        return $this;
    }

    public function removeMealFavori(MealFavoris $mealFavori): self
    {
        if ($this->mealFavoris->contains($mealFavori)) {
            $this->mealFavoris->removeElement($mealFavori);
            // set the owning side to null (unless already changed)
            if ($mealFavori->getMeal() === $this) {
                $mealFavori->setMeal(null);
            }
        }

        return $this;
    }
}
