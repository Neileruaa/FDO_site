<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Club
 * @ORM\Entity(repositoryClass="App\Repository\ClubRepository")
 * @ORM\Table(name="club")
 * @ORM\Entity
 */

class Club implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     */

    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="2",minMessage="la ville doit faire au moins 2 caracteres")

     */


    private $villeClub;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Assert\Length(min="5", max="5", minMessage="le code postal doit faire 5 chiffres")
     */
    private $codePostalClub;



    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Assert\Length(min="10", max="10", minMessage="Votre mot de passe doit faire au moins 6 caracteres")

     */
    private $phoneClub;


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="2", minMessage="Votre prenom doit faire au moins 2 caracteres")
     */
    private $nameClubOwner;

    /**
     * @var string|null
     *
     * @ORM\Column(name="password", type="string", length=100, nullable=true)
     * @Assert\Length(min="6", minMessage="Votre mot de passe doit faire au moins 6 caracteres")
     * @Assert\EqualTo(propertyPath="confirmPassword",message="vos deux mots de passe nes sont as les memes")
     *
     *
     */
    private $password;

    /*
      * @Assert\EqualTo(propertyPath="password", message="vos deux mots de passe nes sont as les memes")
      */
    public $confirmPassword;


    /**
     * @ORM\Column(type="string", length=255,unique=true)
     *@Assert\Email()
     *
     */
    private $emailClub;



    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Team", mappedBy="club", orphanRemoval=true)
     */
    private $teams;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Competition", inversedBy="clubOrganizer")
     */
    private $competition;

    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    private $roles = ['ROLE_USER'];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Dancer", mappedBy="club", orphanRemoval=true)
     */
    private $dancers;


    public function __construct()
    {
        $this->teams = new ArrayCollection();
        $this->dancers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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


    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */


    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }


    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->nameClubOwner,
            $this->villeClub,
            $this->codePostalClub,
            $this->password,
            $this->emailClub,
            $this->phoneClub,
            $this->roles
        ));
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->nameClubOwner,
            $this->villeClub,
            $this->codePostalClub,
            $this->password,
            $this->emailClub,
            $this->phoneClub,
            $this->roles

            ) = unserialize($serialized);
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return array('ROLE_USER');
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return $this->roles;
    }

    public function getVilleClub(): ?string
    {
        return $this->villeClub;
    }

    public function setVilleClub(string $villeClub): self
    {
        $this->villeClub = $villeClub;

        return $this;
    }

    public function getCodePostalClub(): ?string
    {
        return $this->codePostalClub;
    }

    public function setCodePostalClub(?string $codePostalClub): self
    {
        $this->codePostalClub = $codePostalClub;

        return $this;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

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
            $dancer->setClub($this);
        }

        return $this;
    }

    public function removeDancer(Dancer $dancer): self
    {
        if ($this->dancers->contains($dancer)) {
            $this->dancers->removeElement($dancer);
            // set the owning side to null (unless already changed)
            if ($dancer->getClub() === $this) {
                $dancer->setClub(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getUsername();
    }



}
