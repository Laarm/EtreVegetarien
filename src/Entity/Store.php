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
     * @ORM\OneToMany(targetEntity="App\Entity\StoreAvis", mappedBy="store", orphanRemoval=true)
     */
    private $storeAvis;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProduitSync", mappedBy="Store", orphanRemoval=true)
     */
    private $produitSyncs;

    public function __construct()
    {
        $this->storeAvis = new ArrayCollection();
        $this->produitSyncs = new ArrayCollection();
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
     * @return Collection|StoreAvis[]
     */
    public function getStoreAvis(): Collection
    {
        return $this->storeAvis;
    }

    public function addStoreAvi(StoreAvis $storeAvi): self
    {
        if (!$this->storeAvis->contains($storeAvi)) {
            $this->storeAvis[] = $storeAvi;
            $storeAvi->setStore($this);
        }

        return $this;
    }

    public function removeStoreAvi(StoreAvis $storeAvi): self
    {
        if ($this->storeAvis->contains($storeAvi)) {
            $this->storeAvis->removeElement($storeAvi);
            // set the owning side to null (unless already changed)
            if ($storeAvi->getStore() === $this) {
                $storeAvi->setStore(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProduitSync[]
     */
    public function getProduitSyncs(): Collection
    {
        return $this->produitSyncs;
    }

    public function addProduitSync(ProduitSync $produitSync): self
    {
        if (!$this->produitSyncs->contains($produitSync)) {
            $this->produitSyncs[] = $produitSync;
            $produitSync->setStore($this);
        }

        return $this;
    }

    public function removeProduitSync(ProduitSync $produitSync): self
    {
        if ($this->produitSyncs->contains($produitSync)) {
            $this->produitSyncs->removeElement($produitSync);
            // set the owning side to null (unless already changed)
            if ($produitSync->getStore() === $this) {
                $produitSync->setStore(null);
            }
        }

        return $this;
    }
}
