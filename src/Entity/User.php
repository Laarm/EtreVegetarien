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
     * @ORM\OneToMany(targetEntity="App\Entity\MagasinAvis", mappedBy="postedBy")
     */
    private $magasinAvis;

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

    public function __construct()
    {
        $this->magasinAvis = new ArrayCollection();
        $this->restaurantAvis = new ArrayCollection();
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
     * @return Collection|MagasinAvis[]
     */
    public function getMagasinAvis(): Collection
    {
        return $this->magasinAvis;
    }

    public function addMagasinAvi(MagasinAvis $magasinAvi): self
    {
        if (!$this->magasinAvis->contains($magasinAvi)) {
            $this->magasinAvis[] = $magasinAvi;
            $magasinAvi->setPostedBy($this);
        }

        return $this;
    }

    public function removeMagasinAvi(MagasinAvis $magasinAvi): self
    {
        if ($this->magasinAvis->contains($magasinAvi)) {
            $this->magasinAvis->removeElement($magasinAvi);
            // set the owning side to null (unless already changed)
            if ($magasinAvi->getPostedBy() === $this) {
                $magasinAvi->setPostedBy(null);
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
        return ['ROLE_USER'];
    }
}
