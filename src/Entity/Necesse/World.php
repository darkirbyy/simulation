<?php

declare(strict_types=1);

namespace App\Entity\Necesse;

use App\Enum\Necesse\ReplaceModeEnum;
use App\Repository\Necesse\WorldRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: WorldRepository::class)]
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

    #[ORM\Column(enumType: ReplaceModeEnum::class)]
    #[Assert\NotBlank]
    private ?ReplaceModeEnum $replaceMode = null;

    public function __construct()
    {
        $this->eggToChick = new MinMax();
        $this->chickToChicken = new MinMax();
        $this->henToLay = new MinMax();
        $this->roosterToFertilize = new MinMax();
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

    public function setEggToFemale(float $eggToFemale): static
    {
        $this->eggToFemale = $eggToFemale;

        return $this;
    }

    public function getReplaceMode(): ?ReplaceModeEnum
    {
        return $this->replaceMode;
    }

    public function setReplaceMode(ReplaceModeEnum $replaceMode): static
    {
        $this->replaceMode = $replaceMode;

        return $this;
    }
}
