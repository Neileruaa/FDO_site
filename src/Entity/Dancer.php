<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DancerRepository")
 */
class Dancer
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
    private $nameDancer;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstNameDancer;

    /**
     * @ORM\Column(type="date")
     */
    private $dateBirthDancer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $emailDancer;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Team", mappedBy="dancers")
     */
    private $teams;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Club", mappedBy="dancers")
     */
    private $club;

    public function __construct()
    {
        $this->team = new ArrayCollection();
        $this->teams = new ArrayCollection();
        $this->club = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameDancer(): ?string
    {
        return $this->nameDancer;
    }

    public function setNameDancer(string $nameDancer): self
    {
        $this->nameDancer = $nameDancer;

        return $this;
    }

    public function getFirstNameDancer(): ?string
    {
        return $this->firstNameDancer;
    }

    public function setFirstNameDancer(string $firstNameDancer): self
    {
        $this->firstNameDancer = $firstNameDancer;

        return $this;
    }

    public function getDateBirthDancer(): ?\DateTimeInterface
    {
        return $this->dateBirthDancer;
    }

    public function setDateBirthDancer(\DateTimeInterface $dateBirthDancer): self
    {
        $this->dateBirthDancer = $dateBirthDancer;

        return $this;
    }

    public function getEmailDancer(): ?string
    {
        return $this->emailDancer;
    }

    public function setEmailDancer(?string $emailDancer): self
    {
        $this->emailDancer = $emailDancer;

        return $this;
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    /**
     * @return Collection|Club[]
     */
    public function getClub(): Collection
    {
        return $this->club;
    }

    public function addClub(Club $club): self
    {
        if (!$this->club->contains($club)) {
            $this->club[] = $club;
            $club->setDancers($this);
        }

        return $this;
    }

    public function removeClub(Club $club): self
    {
        if ($this->club->contains($club)) {
            $this->club->removeElement($club);
            // set the owning side to null (unless already changed)
            if ($club->getDancers() === $this) {
                $club->setDancers(null);
            }
        }

        return $this;
    }
}
