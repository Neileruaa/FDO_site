<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JudgeRepository")
 */
class Judge
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
    private $nameJudge;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstNameJudge;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameJudge(): ?string
    {
        return $this->nameJudge;
    }

    public function setNameJudge(string $nameJudge): self
    {
        $this->nameJudge = $nameJudge;

        return $this;
    }

    public function getFirstNameJudge(): ?string
    {
        return $this->firstNameJudge;
    }

    public function setFirstNameJudge(string $firstNameJudge): self
    {
        $this->firstNameJudge = $firstNameJudge;

        return $this;
    }
}
