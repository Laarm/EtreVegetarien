<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RepasFavorisRepository")
 */
class RepasFavoris
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="repasFavoris")
     * @ORM\JoinColumn(nullable=false)
     */
    private $postedBy;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Repas", inversedBy="repasFavoris")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Repas;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRepas(): ?Repas
    {
        return $this->Repas;
    }

    public function setRepas(?Repas $Repas): self
    {
        $this->Repas = $Repas;

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
