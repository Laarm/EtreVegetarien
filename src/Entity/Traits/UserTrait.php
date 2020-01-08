<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait UserTrait
{
    /**
     * @var datetime $passwordforgotExpiration
     *
     * @ORM\Column(name="passwordforgot_expiration", type="datetime")
     */
    private $passwordforgotExpiration;

    /**
     * @var string passwordForgot
     *
     * @ORM\Column(name="password_forgot", type="string", length=255, nullable=true)
     */
    private $passwordForgot;

    /**
     * @var string password
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $password;

    /**
     * Get passwordforgotExpiration
     *
     * @return string
     */
    public function getPasswordforgotExpiration(): ?\DateTimeInterface
    {
        return $this->passwordforgotExpiration;
    }

    /**
     * Set passwordforgotExpiration
     *
     * @param \DateTimeInterface|null $passwordforgotExpiration
     * @return UserTrait
     */
    public function setPasswordforgotExpiration(?\DateTimeInterface $passwordforgotExpiration): self
    {
        $this->passwordforgotExpiration = $passwordforgotExpiration;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Set password
     *
     * @param string|null $password
     * @return UserTrait
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get passwordForgot
     *
     * @return string
     */
    public function getPasswordForgot(): ?string
    {
        return $this->passwordForgot;
    }

    /**
     * Set passwordForgot
     *
     * @param string|null $passwordForgot
     * @return UserTrait
     */
    public function setPasswordForgot(?string $passwordForgot): self
    {
        $this->passwordForgot = $passwordForgot;

        return $this;
    }
}