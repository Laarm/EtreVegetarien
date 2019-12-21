<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductFavorisRepository")
 */
class ProductFavoris
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="productFavoris")
     * @ORM\JoinColumn(nullable=false)
     */
    private $postedById;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="productFavoris")
     * @ORM\JoinColumn(nullable=false)
     */
    private $productId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostedById(): ?User
    {
        return $this->postedById;
    }

    public function setPostedById(?User $postedById): self
    {
        $this->postedById = $postedById;

        return $this;
    }

    public function getProductId(): ?Product
    {
        return $this->productId;
    }

    public function setProductId(?Product $productId): self
    {
        $this->productId = $productId;

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
}
