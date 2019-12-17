<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProduitRepository")
 */
class Produit
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
     * @ORM\OneToMany(targetEntity="App\Entity\ProduitFavoris", mappedBy="produitId", orphanRemoval=true)
     */
    private $produitFavoris;

    public function __construct()
    {
        $this->produitFavoris = new ArrayCollection();
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
     * @return Collection|ProduitFavoris[]
     */
    public function getProduitFavoris(): Collection
    {
        return $this->produitFavoris;
    }

    public function addProduitFavori(ProduitFavoris $produitFavori): self
    {
        if (!$this->produitFavoris->contains($produitFavori)) {
            $this->produitFavoris[] = $produitFavori;
            $produitFavori->setProduitId($this);
        }

        return $this;
    }

    public function removeProduitFavori(ProduitFavoris $produitFavori): self
    {
        if ($this->produitFavoris->contains($produitFavori)) {
            $this->produitFavoris->removeElement($produitFavori);
            // set the owning side to null (unless already changed)
            if ($produitFavori->getProduitId() === $this) {
                $produitFavori->setProduitId(null);
            }
        }

        return $this;
    }
}
