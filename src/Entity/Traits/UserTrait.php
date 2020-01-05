<?php

namespace App\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

trait UserTrait
{
    /**
     * @var string bio
     *
     * @ORM\Column(name="bio", type="string", length=255, nullable=true)
     */
    private $bio;

    /**
     * @var string passwordForgot
     *
     * @ORM\Column(name="password_forgot", type="string", length=255, nullable=true)
     */
    private $passwordForgot;

    /**
     * @var string preference
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $preference;

    /**
     * @var string avatar
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;


    /**
     * @var string username
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $username;

    /**
     * @var string password
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $password;

    /**
     * @var string email
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string role
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $role;

    /**
     * Get role
     *
     * @return string
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * Set role
     *
     * @param string|null $role
     * @return UserTrait
     */
    public function setRole(?string $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Set username
     *
     * @param string|null $username
     * @return UserTrait
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

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
     * Get email
     *
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set email
     *
     * @param string|null $email
     * @return UserTrait
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * Set avatar
     *
     * @param string|null $avatar
     * @return UserTrait
     */
    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get preference
     *
     * @return string
     */
    public function getPreference(): ?string
    {
        return $this->preference;
    }

    /**
     * Set preference
     *
     * @param string|null $preference
     * @return UserTrait
     */
    public function setPreference(?string $preference): self
    {
        $this->preference = $preference;

        return $this;
    }

    /**
     * Get bio
     *
     * @return string
     */
    public function getBio(): ?string
    {
        return $this->bio;
    }

    /**
     * Set bio
     *
     * @param string|null $bio
     * @return UserTrait
     */
    public function setBio(?string $bio): self
    {
        $this->bio = $bio;

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