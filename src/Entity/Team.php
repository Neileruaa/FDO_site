<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 */
class Team
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Dance")
     */
    private $dances;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Dancer", inversedBy="teams")
     */
    private $dancers;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Club", inversedBy="teams")
     * @ORM\JoinColumn(nullable=true)
     */
    private $club;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Competition", mappedBy="teams")
     */
    private $competitions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Category", mappedBy="teams")
     */
    private $categories;

    public function __construct()
    {
        $this->Dancer = new ArrayCollection();
        $this->category = new ArrayCollection();
        $this->dances = new ArrayCollection();
        $this->dancers = new ArrayCollection();
        $this->competitions = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection|Dancer[]
     */
    public function getDancers(): Collection
    {
        return $this->dancers;
    }

    public function addDancer(Dancer $dancer): self
    {
        if (!$this->dancers->contains($dancer)) {
            $this->dancers[] = $dancer;
            $dancer->addTeam($this);
        }

        return $this;
    }

    public function removeDancer(Dancer $dancer): self
    {
        if ($this->dancers->contains($dancer)) {
            $this->dancers->removeElement($dancer);
            $dancer->removeTeam($this);
        }

        return $this;
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): self
    {
        $this->club = $club;

        return $this;
    }

    /**
     * @return Collection|Competition[]
     */
    public function getCompetitions(): Collection
    {
        return $this->competitions;
    }

    public function addCompetition(Competition $competition): self
    {
        if (!$this->competitions->contains($competition)) {
            $this->competitions[] = $competition;
            $competition->addTeam($this);
        }

        return $this;
    }

    public function removeCompetition(Competition $competition): self
    {
        if ($this->competitions->contains($competition)) {
            $this->competitions->removeElement($competition);
            $competition->removeTeam($this);
        }

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setTeams($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
            // set the owning side to null (unless already changed)
            if ($category->getTeams() === $this) {
                $category->setTeams(null);
            }
        }

        return $this;
    }
}
