<?php

declare(strict_types=1);

namespace App\Entity\Necesse;

use App\Repository\Necesse\WorldRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: WorldRepository::class)]
#[ORM\UniqueConstraint(fields: ['label'])]
#[UniqueEntity(fields: ['label'])]
class World
{
    // /////////////////////////////////////////////////////
    // All fields and their validation constraints /////////
    // /////////////////////////////////////////////////////

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $label = null;

    #[ORM\Embedded(class: MinMax::class)]
    #[Assert\Valid]
    private ?MinMax $eggToChick = null;

    #[ORM\Embedded(class: MinMax::class)]
    #[Assert\Valid]
    private ?MinMax $chickToChicken = null;

    #[ORM\Embedded(class: MinMax::class)]
    #[Assert\Valid]
    private ?MinMax $henToLay = null;

    #[ORM\Embedded(class: MinMax::class)]
    #[Assert\Valid]
    private ?MinMax $roosterToFertilize = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Range(min: 0, max: 1)]
    private ?float $eggToFemale = null;

    /**
     * @var Collection<int, Sim>
     */
    #[ORM\OneToMany(targetEntity: Sim::class, mappedBy: 'world', orphanRemoval: true, cascade: ['remove'])]
    private Collection $sims;

    // /////////////////////////////////////////////////////
    // Custom methods and validation constraints ///////////
    // /////////////////////////////////////////////////////

    public function __construct()
    {
        $this->eggToChick = new MinMax();
        $this->chickToChicken = new MinMax();
        $this->henToLay = new MinMax();
        $this->roosterToFertilize = new MinMax();
        $this->sims = new ArrayCollection();
    }

    public function setDefaults(): static
    {
        $this->label = 'Mon Monde 1';
        $this->eggToChick->setDefaults();
        $this->chickToChicken->setDefaults();
        $this->henToLay->setDefaults();
        $this->roosterToFertilize->setDefaults();
        $this->eggToFemale = 0.5;

        return $this;
    }

    // /////////////////////////////////////////////////////
    // Doctrine auto-generated getter and setter ///////////
    // /////////////////////////////////////////////////////

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getEggToChick(): MinMax
    {
        return $this->eggToChick;
    }

    public function setEggToChick(MinMax $eggToChick): static
    {
        $this->eggToChick = $eggToChick;

        return $this;
    }

    public function getChickToChicken(): MinMax
    {
        return $this->chickToChicken;
    }

    public function setChickToChicken(MinMax $chickToChicken): static
    {
        $this->chickToChicken = $chickToChicken;

        return $this;
    }

    public function getHenToLay(): MinMax
    {
        return $this->henToLay;
    }

    public function setHenToLay(MinMax $henToLay): static
    {
        $this->henToLay = $henToLay;

        return $this;
    }

    public function getRoosterToFertilize(): MinMax
    {
        return $this->roosterToFertilize;
    }

    public function setRoosterToFertilize(MinMax $roosterToFertilize): static
    {
        $this->roosterToFertilize = $roosterToFertilize;

        return $this;
    }

    public function getEggToFemale(): ?float
    {
        return $this->eggToFemale;
    }

    public function setEggToFemale(?float $eggToFemale): static
    {
        $this->eggToFemale = $eggToFemale;

        return $this;
    }

    /**
     * @return Collection<int, Sim>
     */
    public function getSims(): Collection
    {
        return $this->sims;
    }

    public function addSim(Sim $sim): static
    {
        if (!$this->sims->contains($sim)) {
            $this->sims->add($sim);
            $sim->setWorld($this);
        }

        return $this;
    }

    public function removeSim(Sim $sim): static
    {
        if ($this->sims->removeElement($sim)) {
            // set the owning side to null (unless already changed)
            if ($sim->getWorld() === $this) {
                $sim->setWorld(null);
            }
        }

        return $this;
    }
}
