<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StoreRepository")
 */
class Store
{
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
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $city;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StoreFeedback", mappedBy="store", orphanRemoval=true)
     */
    private $storeFeedback;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductSync", mappedBy="Store", orphanRemoval=true)
     */
    private $productSyncs;

    public function __construct()
    {
        $this->storeFeedback = new ArrayCollection();
        $this->productSyncs = new ArrayCollection();
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

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
            $storeAvi->setStore($this);
        }

        return $this;
    }

    public function removeStoreAvi(StoreFeedback $storeAvi): self
    {
        if ($this->storeFeedback->contains($storeAvi)) {
            $this->storeFeedback->removeElement($storeAvi);
            // set the owning side to null (unless already changed)
            if ($storeAvi->getStore() === $this) {
                $storeAvi->setStore(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProductSync[]
     */
    public function getProductSyncs(): Collection
    {
        return $this->productSyncs;
    }

    public function addProductSync(ProductSync $productSync): self
    {
        if (!$this->productSyncs->contains($productSync)) {
            $this->productSyncs[] = $productSync;
            $productSync->setStore($this);
        }

        return $this;
    }

    public function removeProductSync(ProductSync $productSync): self
    {
        if ($this->productSyncs->contains($productSync)) {
            $this->productSyncs->removeElement($productSync);
            // set the owning side to null (unless already changed)
            if ($productSync->getStore() === $this) {
                $productSync->setStore(null);
            }
        }

        return $this;
    }
}
