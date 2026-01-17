<?php

declare(strict_types=1);

namespace App\Entity\Necesse;

use App\Enum\Necesse\RunEnum;
use App\Repository\Necesse\SimRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SimRepository::class)]
class Sim
{
    // /////////////////////////////////////////////////////
    // All fields and their validation constraints /////////
    // /////////////////////////////////////////////////////

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?float $duration = null;

    #[ORM\Column]
    private ?int $memory = null;

    #[ORM\Column]
    private ?int $iteration = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(0)]
    private ?int $initialHen = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(0)]
    private ?int $initialRooster = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(propertyPath: 'initialHen', message: 'La valeur limite doit être supérieure ou égale à la valeur initiale.')]
    private ?int $limitHen = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(propertyPath: 'initialRooster', message: 'La valeur limite doit être supérieure ou égale à la valeur initiale.')]
    private ?int $limitRooster = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(0)]
    private ?int $limitNest = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(1)]
    private ?int $time = null;

    #[ORM\Column(type: Types::BIGINT)]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(0)]
    private ?int $seed = null;

    #[ORM\Column(enumType: RunEnum::class)]
    #[Assert\NotBlank]
    private ?RunEnum $run = null;

    #[ORM\ManyToOne(inversedBy: 'sims')]
    #[ORM\JoinColumn(nullable: false)]
    private ?World $world = null;

    /**
     * @var Collection<int, Bar>
     */
    #[ORM\OneToMany(targetEntity: Bar::class, mappedBy: 'sim', orphanRemoval: true, cascade: ['persist'])]
    #[ORM\OrderBy(['time' => 'ASC'])]
    private Collection $bars;

    // /////////////////////////////////////////////////////
    // Custom methods and validation constraints ///////////
    // /////////////////////////////////////////////////////

    public function __construct()
    {
        $this->bars = new ArrayCollection();
    }

    public function setDefaults(): static
    {
        $this->initialHen = 1;
        $this->initialRooster = 1;
        $this->limitHen = 10;
        $this->limitRooster = 10;
        $this->limitNest = 10;
        $this->seed = rand(0, 2 ** 32);
        $this->time = 18000;

        return $this;
    }

    // /////////////////////////////////////////////////////
    // Doctrine auto-generated getter and setter ///////////
    // /////////////////////////////////////////////////////

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(?\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getDuration(): ?float
    {
        return $this->duration;
    }

    public function setDuration(?float $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getMemory(): ?int
    {
        return $this->memory;
    }

    public function setMemory(?int $memory): static
    {
        $this->memory = $memory;

        return $this;
    }

    public function getIteration(): ?int
    {
        return $this->iteration;
    }

    public function setIteration(?int $iteration): static
    {
        $this->iteration = $iteration;

        return $this;
    }

    public function getInitialHen(): ?int
    {
        return $this->initialHen;
    }

    public function setInitialHen(?int $initialHen): static
    {
        $this->initialHen = $initialHen;

        return $this;
    }

    public function getInitialRooster(): ?int
    {
        return $this->initialRooster;
    }

    public function setInitialRooster(?int $initialRooster): static
    {
        $this->initialRooster = $initialRooster;

        return $this;
    }

    public function getLimitHen(): ?int
    {
        return $this->limitHen;
    }

    public function setLimitHen(?int $limitHen): static
    {
        $this->limitHen = $limitHen;

        return $this;
    }

    public function getLimitRooster(): ?int
    {
        return $this->limitRooster;
    }

    public function setLimitRooster(?int $limitRooster): static
    {
        $this->limitRooster = $limitRooster;

        return $this;
    }

    public function getLimitNest(): ?int
    {
        return $this->limitNest;
    }

    public function setLimitNest(?int $limitNest): static
    {
        $this->limitNest = $limitNest;

        return $this;
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

    public function getSeed(): ?int
    {
        return $this->seed;
    }

    public function setSeed(?int $seed): static
    {
        $this->seed = $seed;

        return $this;
    }

    public function getRun(): ?RunEnum
    {
        return $this->run;
    }

    public function setRun(?RunEnum $run): static
    {
        $this->run = $run;

        return $this;
    }

    public function getWorld(): ?World
    {
        return $this->world;
    }

    public function setWorld(?World $world): static
    {
        $this->world = $world;

        return $this;
    }

    /**
     * @return Collection<int, Bar>
     */
    public function getBars(): Collection
    {
        return $this->bars;
    }

    public function addBar(Bar $bar): static
    {
        if (!$this->bars->contains($bar)) {
            $this->bars->add($bar);
            $bar->setSim($this);
        }

        return $this;
    }

    public function removeBar(Bar $bar): static
    {
        if ($this->bars->removeElement($bar)) {
            // set the owning side to null (unless already changed)
            if ($bar->getSim() === $this) {
                $bar->setSim(null);
            }
        }

        return $this;
    }
}
