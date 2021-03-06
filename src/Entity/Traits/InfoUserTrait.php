<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait InfoUserTrait
{
    /**
     * @var datetime $preferenceCreatedAt
     *
     * @ORM\Column(name="preference_created_at", type="datetime")
     */
    private $preferenceCreatedAt;

    /**
     * @var string bio
     *
     * @ORM\Column(name="bio", type="string", length=255, nullable=true)
     */
    private $bio;

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
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 5,
     *      max = 20,
     *      minMessage = "Votre pseudonyme doit contenir au moins 5 caractères",
     *      maxMessage = "Votre pseudonyme doit contenir moins de 20 caractères"
     * )
     */
    private $username;

    /**
     * @var string email
     *
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     * @Assert\NotBlank
     * @Assert\Email(
     *     message = "Votre adresse e-mail '{{ value }}' n'est pas valide.",
     *     checkMX = true
     * )
     */
    private $email;

    /**
     * @var string role
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $role;

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
}