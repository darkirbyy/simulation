<?php

declare(strict_types=1);

namespace App\Entity\Necesse;

use App\Repository\Necesse\BarRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: BarRepository::class)]
#[ORM\UniqueConstraint(fields: ['id', 'time'])]
#[UniqueEntity(fields: ['id', 'time'])]
class Bar
{
    // /////////////////////////////////////////////////////
    // All fields and their validation constraints /////////
    // /////////////////////////////////////////////////////

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $time = null;

    #[ORM\Column]
    private ?int $producedEgg = null;

    #[ORM\Column]
    private ?int $producedMeat = null;

    #[ORM\Column]
    private ?int $livingEgg = null;

    #[ORM\Column]
    private ?int $livingChickFemale = null;

    #[ORM\Column]
    private ?int $livingChickMale = null;

    #[ORM\Column]
    private ?int $livingHenVirgo = null;

    #[ORM\Column]
    private ?int $livingHenFertilized = null;

    #[ORM\Column]
    private ?int $livingRooster = null;

    #[ORM\ManyToOne(inversedBy: 'bars')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sim $sim = null;

    // /////////////////////////////////////////////////////
    // Custom methods and validation constraints ///////////
    // /////////////////////////////////////////////////////

    public function __construct() {}

    // /////////////////////////////////////////////////////
    // Doctrine auto-generated getter and setter ///////////
    // /////////////////////////////////////////////////////

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(?int $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function getProducedEgg(): ?int
    {
        return $this->producedEgg;
    }

    public function setProducedEgg(?int $producedEgg): static
    {
        $this->producedEgg = $producedEgg;

        return $this;
    }

    public function getProducedMeat(): ?int
    {
        return $this->producedMeat;
    }

    public function setProducedMeat(?int $producedMeat): static
    {
        $this->producedMeat = $producedMeat;

        return $this;
    }

    public function getLivingEgg(): ?int
    {
        return $this->livingEgg;
    }

    public function setLivingEgg(?int $livingEgg): static
    {
        $this->livingEgg = $livingEgg;

        return $this;
    }

    public function getLivingChickFemale(): ?int
    {
        return $this->livingChickFemale;
    }

    public function setLivingChickFemale(?int $livingChickFemale): static
    {
        $this->livingChickFemale = $livingChickFemale;

        return $this;
    }

    public function getLivingChickMale(): ?int
    {
        return $this->livingChickMale;
    }

    public function setLivingChickMale(?int $livingChickMale): static
    {
        $this->livingChickMale = $livingChickMale;

        return $this;
    }

    public function getLivingHenVirgo(): ?int
    {
        return $this->livingHenVirgo;
    }

    public function setLivingHenVirgo(?int $livingHenVirgo): static
    {
        $this->livingHenVirgo = $livingHenVirgo;

        return $this;
    }

    public function getLivingHenFertilized(): ?int
    {
        return $this->livingHenFertilized;
    }

    public function setLivingHenFertilized(?int $livingHenFertilized): static
    {
        $this->livingHenFertilized = $livingHenFertilized;

        return $this;
    }

    public function getLivingRooster(): ?int
    {
        return $this->livingRooster;
    }

    public function setLivingRooster(?int $livingRooster): static
    {
        $this->livingRooster = $livingRooster;

        return $this;
    }

    public function getSim(): ?Sim
    {
        return $this->sim;
    }

    public function setSim(?Sim $sim): static
    {
        $this->sim = $sim;

        return $this;
    }
}
