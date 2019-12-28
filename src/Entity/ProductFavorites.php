<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductFavoritesRepository")
 */
class ProductFavorites
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="productFavorites")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
    private $postedById;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="productFavorites")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
    private $productId;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
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
