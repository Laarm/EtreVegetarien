<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RestaurantRepository")
 */
class Restaurant
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
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ville;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RestaurantAvis", mappedBy="restaurant")
     */
    private $restaurantAvis;

    public function __construct()
    {
        $this->restaurantAvis = new ArrayCollection();
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

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
            $restaurantAvi->setRestaurant($this);
        }

        return $this;
    }

    public function removeRestaurantAvi(RestaurantAvis $restaurantAvi): self
    {
        if ($this->restaurantAvis->contains($restaurantAvi)) {
            $this->restaurantAvis->removeElement($restaurantAvi);
            // set the owning side to null (unless already changed)
            if ($restaurantAvi->getRestaurant() === $this) {
                $restaurantAvi->setRestaurant(null);
            }
        }

        return $this;
    }
}
