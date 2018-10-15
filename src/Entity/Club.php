<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClubRepository")
 */
class Club
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
    private $nameClub;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nameClubOwner;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $passwordClub;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $emailClub;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $phoneClub;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Team", mappedBy="club", orphanRemoval=true)
     */
    private $teams;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Competition", inversedBy="clubOrganizer")
     */
    private $competition;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Dancer", inversedBy="club")
     */
    private $dancers;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameClub(): ?string
    {
        return $this->nameClub;
    }

    public function setNameClub(string $nameClub): self
    {
        $this->nameClub = $nameClub;

        return $this;
    }

    public function getNameClubOwner(): ?string
    {
        return $this->nameClubOwner;
    }

    public function setNameClubOwner(string $nameClubOwner): self
    {
        $this->nameClubOwner = $nameClubOwner;

        return $this;
    }

    public function getPasswordClub(): ?string
    {
        return $this->passwordClub;
    }

    public function setPasswordClub(string $passwordClub): self
    {
        $this->passwordClub = $passwordClub;

        return $this;
    }

    public function getEmailClub(): ?string
    {
        return $this->emailClub;
    }

    public function setEmailClub(string $emailClub): self
    {
        $this->emailClub = $emailClub;

        return $this;
    }

    public function getPhoneClub(): ?string
    {
        return $this->phoneClub;
    }

    public function setPhoneClub(?string $phoneClub): self
    {
        $this->phoneClub = $phoneClub;

        return $this;
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams[] = $team;
            $team->setClub($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->teams->contains($team)) {
            $this->teams->removeElement($team);
            // set the owning side to null (unless already changed)
            if ($team->getClub() === $this) {
                $team->setClub(null);
            }
        }

        return $this;
    }

    public function getCompetition(): ?Competition
    {
        return $this->competition;
    }

    public function setCompetition(?Competition $competition): self
    {
        $this->competition = $competition;

        return $this;
    }

    public function getDancers(): ?Dancer
    {
        return $this->dancers;
    }

    public function setDancers(?Dancer $dancers): self
    {
        $this->dancers = $dancers;

        return $this;
    }
}
