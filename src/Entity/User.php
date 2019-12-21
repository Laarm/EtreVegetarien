<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
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
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $preference;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StoreAvis", mappedBy="postedBy")
     */
    private $storeAvis;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RestaurantAvis", mappedBy="postedBy")
     */
    private $restaurantAvis;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facebook;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $instagram;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $youtube;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $twitter;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $preferenceDepuis;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Meal", mappedBy="postedBy", orphanRemoval=true)
     */
    private $meal;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductFavorites", mappedBy="postedById", orphanRemoval=true)
     */
    private $productFavorites;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $PreferenceCreatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MealFavorites", mappedBy="postedBy", orphanRemoval=true)
     */
    private $mealFavorites;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bio;

    public function __construct()
    {
        $this->storeAvis = new ArrayCollection();
        $this->restaurantAvis = new ArrayCollection();
        $this->meal = new ArrayCollection();
        $this->productFavorites = new ArrayCollection();
        $this->mealFavorites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPreference(): ?string
    {
        return $this->preference;
    }

    public function setPreference(?string $preference): self
    {
        $this->preference = $preference;

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

    /**
     * @return Collection|StoreAvis[]
     */
    public function getStoreAvis(): Collection
    {
        return $this->storeAvis;
    }

    public function addStoreAvi(StoreAvis $storeAvi): self
    {
        if (!$this->storeAvis->contains($storeAvi)) {
            $this->storeAvis[] = $storeAvi;
            $storeAvi->setPostedBy($this);
        }

        return $this;
    }

    public function removeStoreAvi(StoreAvis $storeAvi): self
    {
        if ($this->storeAvis->contains($storeAvi)) {
            $this->storeAvis->removeElement($storeAvi);
            // set the owning side to null (unless already changed)
            if ($storeAvi->getPostedBy() === $this) {
                $storeAvi->setPostedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|RestaurantAvis[]
     */
    public function getRestaurantAvis(): Collection
    {
        return $this->restaurantAvis;
    }

    public function addRestaurantAvi(RestaurantAvis $restaurantAvi): self
    {
        if (!$this->restaurantAvis->contains($restaurantAvi)) {
            $this->restaurantAvis[] = $restaurantAvi;
            $restaurantAvi->setPostedBy($this);
        }

        return $this;
    }

    public function removeRestaurantAvi(RestaurantAvis $restaurantAvi): self
    {
        if ($this->restaurantAvis->contains($restaurantAvi)) {
            $this->restaurantAvis->removeElement($restaurantAvi);
            // set the owning side to null (unless already changed)
            if ($restaurantAvi->getPostedBy() === $this) {
                $restaurantAvi->setPostedBy(null);
            }
        }

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(?string $instagram): self
    {
        $this->instagram = $instagram;

        return $this;
    }

    public function getYoutube(): ?string
    {
        return $this->youtube;
    }

    public function setYoutube(?string $youtube): self
    {
        $this->youtube = $youtube;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getPreferenceDepuis(): ?\DateTimeInterface
    {
        return $this->preferenceDepuis;
    }

    public function setPreferenceDepuis(?\DateTimeInterface $preferenceDepuis): self
    {
        $this->preferenceDepuis = $preferenceDepuis;

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

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;

        return $this;
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

    public function getPreferenceCreatedAt(): ?\DateTimeInterface
    {
        return $this->PreferenceCreatedAt;
    }

    public function setPreferenceCreatedAt(?\DateTimeInterface $PreferenceCreatedAt): self
    {
        $this->PreferenceCreatedAt = $PreferenceCreatedAt;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

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

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }
}
