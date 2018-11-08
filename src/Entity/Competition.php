<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompetitionRepository")
 */
class Competition
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="date")
     */
    private $dateCompetition;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Team", inversedBy="competitions")
     */
    private $teams;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Place", mappedBy="competition")
     */
    private $place;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Dance")
     */
    private $dances;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Club", mappedBy="competition")
     */
    private $clubOrganizer;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
        $this->place = new ArrayCollection();
        $this->dances = new ArrayCollection();
        $this->clubOrganizer = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCompetition(): ?\DateTimeInterface
    {
        return $this->dateCompetition;
    }

    public function setDateCompetition(\DateTimeInterface $dateCompetition): self
    {
        $this->dateCompetition = $dateCompetition;

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
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->teams->contains($team)) {
            $this->teams->removeElement($team);
        }

        return $this;
    }

    /**
     * @return Collection|Place[]
     */
    public function getPlace(): Collection
    {
        return $this->place;
    }

    public function addPlace(Place $place): self
    {
        if (!$this->place->contains($place)) {
            $this->place[] = $place;
            $place->setCompetition($this);
        }

        return $this;
    }

    public function removePlace(Place $place): self
    {
        if ($this->place->contains($place)) {
            $this->place->removeElement($place);
            // set the owning side to null (unless already changed)
            if ($place->getCompetition() === $this) {
                $place->setCompetition(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Dance[]
     */
    public function getDances(): Collection
    {
        return $this->dances;
    }

    public function addDance(Dance $dance): self
    {
        if (!$this->dances->contains($dance)) {
            $this->dances[] = $dance;
        }

        return $this;
    }

    public function removeDance(Dance $dance): self
    {
        if ($this->dances->contains($dance)) {
            $this->dances->removeElement($dance);
        }

        return $this;
    }

    /**
     * @return Collection|Club[]
     */
    public function getClubOrganizer(): Collection
    {
        return $this->clubOrganizer;
    }

    public function addClubOrganizer(Club $clubOrganizer): self
    {
        if (!$this->clubOrganizer->contains($clubOrganizer)) {
            $this->clubOrganizer[] = $clubOrganizer;
            $clubOrganizer->setCompetition($this);
        }

        return $this;
    }

    public function removeClubOrganizer(Club $clubOrganizer): self
    {
        if ($this->clubOrganizer->contains($clubOrganizer)) {
            $this->clubOrganizer->removeElement($clubOrganizer);
            // set the owning side to null (unless already changed)
            if ($clubOrganizer->getCompetition() === $this) {
                $clubOrganizer->setCompetition(null);
            }
        }

        return $this;
    }
}
