<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
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
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductFavorites", mappedBy="productId", orphanRemoval=true)
     */
    private $productFavorites;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductSync", mappedBy="Product", orphanRemoval=true)
     */
    private $productSyncs;

    public function __construct()
    {
        $this->productFavorites = new ArrayCollection();
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
            $productFavorite->setProductId($this);
        }

        return $this;
    }

    public function removeProductFavorite(ProductFavorites $productFavorite): self
    {
        if ($this->productFavorites->contains($productFavorite)) {
            $this->productFavorites->removeElement($productFavorite);
            // set the owning side to null (unless already changed)
            if ($productFavorite->getProductId() === $this) {
                $productFavorite->setProductId(null);
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
