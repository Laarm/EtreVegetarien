<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserParams
 *
 * @ORM\Table(name="user_params")
 * @ORM\Entity
 */
class UserParams
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
     * @var string
     *
     * @ORM\Column(name="account_id", type="string", length=255, nullable=false, options={"default"="''"})
     */
    private $accountId = '\'\'';

    /**
     * @var string|null
     *
     * @ORM\Column(name="param_id", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $paramId = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="info", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $info = 'NULL';

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

    public function getAccountId(): ?string
    {
        return $this->accountId;
    }

    public function setAccountId(string $accountId): self
    {
        $this->accountId = $accountId;

        return $this;
    }

    public function getParamId(): ?string
    {
        return $this->paramId;
    }

    public function setParamId(?string $paramId): self
    {
        $this->paramId = $paramId;

        return $this;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function setInfo(?string $info): self
    {
        $this->info = $info;

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
