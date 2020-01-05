<?php

namespace App\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

trait TimestampableTrait
{
    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Assert\NotBlank
     */
    private $createdAt;

    /**
     * Get createdAt
     *
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTimeInterface $createdAt
     * @return TimestampableTrait
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get preferenceCreatedAt
     *
     * @return datetime
     */
    public function getPreferenceCreatedAt(): ?\DateTimeInterface
    {
        return $this->preferenceCreatedAt;
    }

    /**
     * Set preferenceCreatedAt
     *
     * @param \DateTimeInterface $preferenceCreatedAt
     * @return TimestampableTrait
     */
    public function setPreferenceCreatedAt(?\DateTimeInterface $preferenceCreatedAt): self
    {
        $this->preferenceCreatedAt = $preferenceCreatedAt;

        return $this;
    }

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
}