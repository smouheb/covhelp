<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 */
class Person
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
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phonenumber;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isHelping;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Address", mappedBy="person", cascade={"persist"})
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OptionForHelp", mappedBy="person", cascade={"persist"})
     */
    private $optionsForHelp;

    public function __construct()
    {
        $this->address = new ArrayCollection();
        $this->optionsForHelp = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhonenumber(): ?string
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(?string $phonenumber): self
    {
        $this->phonenumber = $phonenumber;

        return $this;
    }

    public function getIsHelping(): ?bool
    {
        return $this->isHelping;
    }

    public function setIsHelping(bool $isHelping): self
    {
        $this->isHelping = $isHelping;

        return $this;
    }

    /**
     * @return Collection|Address[]
     */
    public function getAddress(): Collection
    {
        return $this->address;
    }

    public function addAddres(Address $address): self
    {
        if (!$this->address->contains($address)) {
            $this->address[] = $address;
            $address->setPerson($this);
        }

        return $this;
    }

    public function removeAddres(Address $address): self
    {
        if ($this->address->contains($address)) {
            $this->address->removeElement($address);
            // set the owning side to null (unless already changed)
            if ($address->getPerson() === $this) {
                $address->setPerson(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|OptionForHelp[]
     */
    public function getOptionsForHelp(): Collection
    {
        return $this->optionsForHelp;
    }

    public function addOptionsForHelp(OptionForHelp $optionsForHelp): self
    {
        if (!$this->optionsForHelp->contains($optionsForHelp)) {
            $this->optionsForHelp[] = $optionsForHelp;
            $optionsForHelp->setPerson($this);
        }

        return $this;
    }

    public function removeOptionsForHelp(OptionForHelp $optionsForHelp): self
    {
        if ($this->optionsForHelp->contains($optionsForHelp)) {
            $this->optionsForHelp->removeElement($optionsForHelp);
            // set the owning side to null (unless already changed)
            if ($optionsForHelp->getPerson() === $this) {
                $optionsForHelp->setPerson(null);
            }
        }

        return $this;
    }
}
