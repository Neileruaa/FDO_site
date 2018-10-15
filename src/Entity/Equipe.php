<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EquipeRepository")
 */
class Equipe
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
    private $Categorie;

    /**
     * @ORM\Column(type="integer")
     */
    private $numero_dossard;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Danseur", mappedBy="teams")
     */
    private $danseurs;

    public function __construct()
    {
        $this->danseurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorie(): ?string
    {
        return $this->Categorie;
    }

    public function setCategorie(string $Categorie): self
    {
        $this->Categorie = $Categorie;

        return $this;
    }

    public function getNumeroDossard(): ?int
    {
        return $this->numero_dossard;
    }

    public function setNumeroDossard(int $numero_dossard): self
    {
        $this->numero_dossard = $numero_dossard;

        return $this;
    }

    /**
     * @return Collection|Danseur[]
     */
    public function getDanseurs(): Collection
    {
        return $this->danseurs;
    }

    public function addDanseur(Danseur $danseur): self
    {
        if (!$this->danseurs->contains($danseur)) {
            $this->danseurs[] = $danseur;
            $danseur->addTeam($this);
        }

        return $this;
    }

    public function removeDanseur(Danseur $danseur): self
    {
        if ($this->danseurs->contains($danseur)) {
            $this->danseurs->removeElement($danseur);
            $danseur->removeTeam($this);
        }

        return $this;
    }
}
