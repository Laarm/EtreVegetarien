<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MealRepository")
 */
class Meal
{
    use TimestampableTrait;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    private $recipe;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="meal")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     * @Assert\NotBlank
     */
    private $postedBy;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MealFavorites", mappedBy="Meal", orphanRemoval=true)
     */
    private $mealFavorites;

    public function __construct()
    {
        $this->mealFavorites = new ArrayCollection();
    }

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

    public function getRecipe(): ?string
    {
        return $this->recipe;
    }

    public function setRecipe(string $recipe): self
    {
        $this->recipe = $recipe;

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
     * @return Collection|MealFavorites[]
     */
    public function getMealFavorites(): Collection
    {
        return $this->mealFavorites;
    }

    public function addMealFavorite(MealFavorites $mealFavorite): self
    {
        if (!$this->mealFavorites->contains($mealFavorite)) {
            $this->mealFavorites[] = $mealFavorite;
            $mealFavorite->setMeal($this);
        }

        return $this;
    }

    public function removeMealFavorite(MealFavorites $mealFavorite): self
    {
        if ($this->mealFavorites->contains($mealFavorite)) {
            $this->mealFavorites->removeElement($mealFavorite);
            // set the owning side to null (unless already changed)
            if ($mealFavorite->getMeal() === $this) {
                $mealFavorite->setMeal(null);
            }
        }

        return $this;
    }
}
