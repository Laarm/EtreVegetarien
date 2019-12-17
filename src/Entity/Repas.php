<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RepasRepository")
 */
class Repas
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
     * @ORM\Column(type="text")
     */
    private $recette;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="repas")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $postedBy;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RepasFavoris", mappedBy="Repas", orphanRemoval=true)
     */
    private $repasFavoris;

    public function __construct()
    {
        $this->repasFavoris = new ArrayCollection();
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

    public function getRecette(): ?string
    {
        return $this->recette;
    }

    public function setRecette(string $recette): self
    {
        $this->recette = $recette;

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

    public function getPostedBy(): ?User
    {
        return $this->postedBy;
    }

    public function setPostedBy(?User $postedBy): self
    {
        $this->postedBy = $postedBy;

        return $this;
    }

    /**
     * @return Collection|RepasFavoris[]
     */
    public function getRepasFavoris(): Collection
    {
        return $this->repasFavoris;
    }

    public function addRepasFavori(RepasFavoris $repasFavori): self
    {
        if (!$this->repasFavoris->contains($repasFavori)) {
            $this->repasFavoris[] = $repasFavori;
            $repasFavori->setRepas($this);
        }

        return $this;
    }

    public function removeRepasFavori(RepasFavoris $repasFavori): self
    {
        if ($this->repasFavoris->contains($repasFavori)) {
            $this->repasFavoris->removeElement($repasFavori);
            // set the owning side to null (unless already changed)
            if ($repasFavori->getRepas() === $this) {
                $repasFavori->setRepas(null);
            }
        }

        return $this;
    }
}
