<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlaceRepository")
 */
class Place
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
    private $cityPlace;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $addressPlace;

    /**
     * @ORM\Column(type="integer")
     */
    private $PostalCode;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCityPlace(): ?string
    {
        return $this->cityPlace;
    }

    public function setCityPlace(string $cityPlace): self
    {
        $this->cityPlace = $cityPlace;

        return $this;
    }

    public function getAddressPlace(): ?string
    {
        return $this->addressPlace;
    }

    public function setAddressPlace(string $addressPlace): self
    {
        $this->addressPlace = $addressPlace;

        return $this;
    }

    public function getPostalCode(): ?int
    {
        return $this->PostalCode;
    }

    public function setPostalCode(int $PostalCode): self
    {
        $this->PostalCode = $PostalCode;

        return $this;
    }


}
