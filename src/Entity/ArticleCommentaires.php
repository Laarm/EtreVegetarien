<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ArticleCommentaires
 *
 * @ORM\Table(name="article_commentaires")
 * @ORM\Entity
 */
class ArticleCommentaires
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $username = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="account_id", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $accountId = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="contenu", type="text", length=65535, nullable=true, options={"default"="NULL"})
     */
    private $contenu = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="created", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $created = 'NULL';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getAccountId(): ?string
    {
        return $this->accountId;
    }

    public function setAccountId(?string $accountId): self
    {
        $this->accountId = $accountId;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getCreated(): ?string
    {
        return $this->created;
    }

    public function setCreated(?string $created): self
    {
        $this->created = $created;

        return $this;
    }


}
