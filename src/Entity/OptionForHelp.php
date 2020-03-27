<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OptionForHelpRepository")
 */
class OptionForHelp
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $groceries;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $garbage;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $walkingDog;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $dryCleaning;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $deliverTakeAway;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="optionsForHelp")
     */
    private $person;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroceries(): ?string
    {
        return $this->groceries;
    }

    public function setGroceries(?string $groceries): self
    {
        $this->groceries = $groceries;

        return $this;
    }

    public function getGarbage(): ?string
    {
        return $this->garbage;
    }

    public function setGarbage(?string $garbage): self
    {
        $this->garbage = $garbage;

        return $this;
    }

    public function getWalkingDog(): ?string
    {
        return $this->walkingDog;
    }

    public function setWalkingDog(?string $walkingDog): self
    {
        $this->walkingDog = $walkingDog;

        return $this;
    }

    public function getDryCleaning(): ?string
    {
        return $this->dryCleaning;
    }

    public function setDryCleaning(?string $dryCleaning): self
    {
        $this->dryCleaning = $dryCleaning;

        return $this;
    }

    public function getDeliverTakeAway(): ?string
    {
        return $this->deliverTakeAway;
    }

    public function setDeliverTakeAway(?string $deliverTakeAway): self
    {
        $this->deliverTakeAway = $deliverTakeAway;

        return $this;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): self
    {
        $this->person = $person;

        return $this;
    }
}
