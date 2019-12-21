<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
