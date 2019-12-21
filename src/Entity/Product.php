<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
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
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductFavoris", mappedBy="productId", orphanRemoval=true)
     */
    private $productFavoris;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductSync", mappedBy="Product", orphanRemoval=true)
     */
    private $productSyncs;

    public function __construct()
    {
        $this->productFavoris = new ArrayCollection();
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
     * @return Collection|ProductFavoris[]
     */
    public function getProductFavoris(): Collection
    {
        return $this->productFavoris;
    }

    public function addProductFavori(ProductFavoris $productFavori): self
    {
        if (!$this->productFavoris->contains($productFavori)) {
            $this->productFavoris[] = $productFavori;
            $productFavori->setProductId($this);
        }

        return $this;
    }

    public function removeProductFavori(ProductFavoris $productFavori): self
    {
        if ($this->productFavoris->contains($productFavori)) {
            $this->productFavoris->removeElement($productFavori);
            // set the owning side to null (unless already changed)
            if ($productFavori->getProductId() === $this) {
                $productFavori->setProductId(null);
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
            $productSync->setProduct($this);
        }

        return $this;
    }

    public function removeProductSync(ProductSync $productSync): self
    {
        if ($this->productSyncs->contains($productSync)) {
            $this->productSyncs->removeElement($productSync);
            // set the owning side to null (unless already changed)
            if ($productSync->getProduct() === $this) {
                $productSync->setProduct(null);
            }
        }

        return $this;
    }
}
