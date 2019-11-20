<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
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
    private $contenu;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ArticleCommentaire", mappedBy="article", orphanRemoval=true)
     */
    private $articleCommentaires;

    public function __construct()
    {
        $this->articleCommentaires = new ArrayCollection();
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

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

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
     * @return Collection|ArticleCommentaire[]
     */
    public function getArticleCommentaires(): Collection
    {
        return $this->articleCommentaires;
    }

    public function addArticleCommentaire(ArticleCommentaire $articleCommentaire): self
    {
        if (!$this->articleCommentaires->contains($articleCommentaire)) {
            $this->articleCommentaires[] = $articleCommentaire;
            $articleCommentaire->setArticle($this);
        }

        return $this;
    }

    public function removeArticleCommentaire(ArticleCommentaire $articleCommentaire): self
    {
        if ($this->articleCommentaires->contains($articleCommentaire)) {
            $this->articleCommentaires->removeElement($articleCommentaire);
            // set the owning side to null (unless already changed)
            if ($articleCommentaire->getArticle() === $this) {
                $articleCommentaire->setArticle(null);
            }
        }

        return $this;
    }
}
