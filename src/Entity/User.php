<?php

namespace App\Entity;

use App\Entity\Traits\SocialNetworkTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Entity\Traits\UserTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    use TimestampableTrait;
    use SocialNetworkTrait;
    use UserTrait;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StoreFeedback", mappedBy="postedBy")
     * @Assert\NotBlank
     */
    private $storeFeedback;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RestaurantFeedback", mappedBy="postedBy")
     * @Assert\NotBlank
     */
    private $restaurantFeedback;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $preferenceCreatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Meal", mappedBy="postedBy", orphanRemoval=true)
     * @Assert\NotBlank
     */
    private $meal;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductFavorites", mappedBy="postedById", orphanRemoval=true)
     * @Assert\NotBlank
     */
    private $productFavorites;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MealFavorites", mappedBy="postedBy", orphanRemoval=true)
     * @Assert\NotBlank
     */
    private $mealFavorites;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $passwordforgotExpiration;

    public function __construct()
    {
        $this->storeFeedback = new ArrayCollection();
        $this->restaurantFeedback = new ArrayCollection();
        $this->meal = new ArrayCollection();
        $this->productFavorites = new ArrayCollection();
        $this->mealFavorites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|StoreFeedback[]
     */
    public function getStoreFeedback(): Collection
    {
        return $this->storeFeedback;
    }

    public function addStoreAvi(StoreFeedback $storeAvi): self
    {
        if (!$this->storeFeedback->contains($storeAvi)) {
            $this->storeFeedback[] = $storeAvi;
            $storeAvi->setPostedBy($this);
        }

        return $this;
    }

    public function removeStoreAvi(StoreFeedback $storeAvi): self
    {
        if ($this->storeFeedback->contains($storeAvi)) {
            $this->storeFeedback->removeElement($storeAvi);
            // set the owning side to null (unless already changed)
            if ($storeAvi->getPostedBy() === $this) {
                $storeAvi->setPostedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|RestaurantFeedback[]
     */
    public function getRestaurantFeedback(): Collection
    {
        return $this->restaurantFeedback;
    }

    public function addRestaurantAvi(RestaurantFeedback $restaurantAvi): self
    {
        if (!$this->restaurantFeedback->contains($restaurantAvi)) {
            $this->restaurantFeedback[] = $restaurantAvi;
            $restaurantAvi->setPostedBy($this);
        }

        return $this;
    }

    public function removeRestaurantAvi(RestaurantFeedback $restaurantAvi): self
    {
        if ($this->restaurantFeedback->contains($restaurantAvi)) {
            $this->restaurantFeedback->removeElement($restaurantAvi);
            // set the owning side to null (unless already changed)
            if ($restaurantAvi->getPostedBy() === $this) {
                $restaurantAvi->setPostedBy(null);
            }
        }

        return $this;
    }

    public function eraseCredentials()
    {
    }

    public function getSalt()
    {
    }

    public function getRoles()
    {
        if ($this->role == null) {
            return ['ROLE_USER'];
        }
        return [$this->role];
    }

    /**
     * @return Collection|Meal[]
     */
    public function getMeal(): Collection
    {
        return $this->meal;
    }

    public function addRepa(Meal $mea): self
    {
        if (!$this->meal->contains($mea)) {
            $this->meal[] = $mea;
            $mea->setPostedBy($this);
        }

        return $this;
    }

    public function removeRepa(Meal $mea): self
    {
        if ($this->meal->contains($mea)) {
            $this->meal->removeElement($mea);
            // set the owning side to null (unless already changed)
            if ($mea->getPostedBy() === $this) {
                $mea->setPostedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProductFavorites[]
     */
    public function getProductFavorites(): Collection
    {
        return $this->productFavorites;
    }

    public function addProductFavorite(ProductFavorites $productFavorite): self
    {
        if (!$this->productFavorites->contains($productFavorite)) {
            $this->productFavorites[] = $productFavorite;
            $productFavorite->setPostedById($this);
        }

        return $this;
    }

    public function removeProductFavorite(ProductFavorites $productFavorite): self
    {
        if ($this->productFavorites->contains($productFavorite)) {
            $this->productFavorites->removeElement($productFavorite);
            // set the owning side to null (unless already changed)
            if ($productFavorite->getPostedById() === $this) {
                $productFavorite->setPostedById(null);
            }
        }

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
            $mealFavorite->setPostedBy($this);
        }

        return $this;
    }

    public function removeMealFavorite(MealFavorites $mealFavorite): self
    {
        if ($this->mealFavorites->contains($mealFavorite)) {
            $this->mealFavorites->removeElement($mealFavorite);
            // set the owning side to null (unless already changed)
            if ($mealFavorite->getPostedBy() === $this) {
                $mealFavorite->setPostedBy(null);
            }
        }

        return $this;
    }
}