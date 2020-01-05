<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MealFavoritesRepository")
 */
class MealFavorites
{
    use TimestampableTrait;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="mealFavorites")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
    private $postedBy;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Meal", inversedBy="mealFavorites")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
    private $Meal;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMeal(): ?Meal
    {
        return $this->Meal;
    }

    public function setMeal(?Meal $Meal): self
    {
        $this->Meal = $Meal;

        return $this;
    }
}
