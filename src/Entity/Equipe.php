<?php

namespace App\Entity;

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
}
