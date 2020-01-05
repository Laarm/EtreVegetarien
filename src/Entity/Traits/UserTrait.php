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