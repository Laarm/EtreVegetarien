<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait SocialNetworkTrait
{
    /**
     * @var string facebook
     *
     * @ORM\Column(name="facebook", type="string", length=255, nullable=true)
     */
    private $facebook;

    /**
     * @var string instagram
     *
     * @ORM\Column(name="instagram", type="string", length=255, nullable=true)
     */
    private $instagram;

    /**
     * @var string youtube
     *
     * @ORM\Column(name="youtube", type="string", length=255, nullable=true)
     */
    private $youtube;

    /**
     * @var string twitter
     *
     * @ORM\Column(name="twitter", type="string", length=255, nullable=true)
     */
    private $twitter;

    /**
     * Get facebook
     *
     * @return string
     */
    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    /**
     * Set facebook
     *
     * @param string|null $facebook
     * @return SocialNetworkTrait
     */
    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * Get instagram
     *
     * @return string
     */
    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    /**
     * Set instagram
     *
     * @param string|null $instagram
     * @return SocialNetworkTrait
     */
    public function setInstagram(?string $instagram): self
    {
        $this->instagram = $instagram;

        return $this;
    }

    /**
     * Get youtube
     *
     * @return string
     */
    public function getYoutube(): ?string
    {
        return $this->youtube;
    }

    /**
     * Set youtube
     *
     * @param string|null $youtube
     * @return SocialNetworkTrait
     */
    public function setYoutube(?string $youtube): self
    {
        $this->youtube = $youtube;

        return $this;
    }

    /**
     * Get twitter
     *
     * @return string
     */
    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    /**
     * Set twitter
     *
     * @param string|null $twitter
     * @return SocialNetworkTrait
     */
    public function setTwitter(?string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }
}