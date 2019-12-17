<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProduitFavorisRepository")
 */
class ProduitFavoris
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="produitFavoris")
     * @ORM\JoinColumn(nullable=false)
     */
    private $postedById;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Produit", inversedBy="produitFavoris")
     * @ORM\JoinColumn(nullable=false)
     */
    private $produitId;

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

    public function getProduitId(): ?Produit
    {
        return $this->produitId;
    }

    public function setProduitId(?Produit $produitId): self
    {
        $this->produitId = $produitId;

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
