<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MagasinRepository")
 */
class Magasin
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
     * @ORM\OneToMany(targetEntity="App\Entity\MagasinAvis", mappedBy="magasin", orphanRemoval=true)
     */
    private $magasinAvis;

    public function __construct()
    {
        $this->magasinAvis = new ArrayCollection();
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
     * @return Collection|MagasinAvis[]
     */
    public function getMagasinAvis(): Collection
    {
        return $this->magasinAvis;
    }

    public function addMagasinAvi(MagasinAvis $magasinAvi): self
    {
        if (!$this->magasinAvis->contains($magasinAvi)) {
            $this->magasinAvis[] = $magasinAvi;
            $magasinAvi->setMagasin($this);
        }

        return $this;
    }

    public function removeMagasinAvi(MagasinAvis $magasinAvi): self
    {
        if ($this->magasinAvis->contains($magasinAvi)) {
            $this->magasinAvis->removeElement($magasinAvi);
            // set the owning side to null (unless already changed)
            if ($magasinAvi->getMagasin() === $this) {
                $magasinAvi->setMagasin(null);
            }
        }

        return $this;
    }
}
