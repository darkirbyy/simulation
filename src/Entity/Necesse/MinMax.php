<?php

declare(strict_types=1);

namespace App\Entity\Necesse;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Embeddable]
class MinMax
{
    // /////////////////////////////////////////////////////
    // All fields and their validation constraints /////////
    // /////////////////////////////////////////////////////

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(1)]
    private ?int $min = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(propertyPath: 'min', message: 'La valeur maximum doit être supérieure ou égale à la valeur minimum.')]
    private ?int $max = null;

    // /////////////////////////////////////////////////////
    // Custom methods and validation constraints ///////////
    // /////////////////////////////////////////////////////

    public function setDefaults(): static
    {
        $this->min = 900;
        $this->max = 1200;

        return $this;
    }

    // /////////////////////////////////////////////////////
    // Doctrine auto-generated getter and setter ///////////
    // /////////////////////////////////////////////////////

    public function getMin(): ?int
    {
        return $this->min;
    }

    public function setMin(?int $min): static
    {
        $this->min = $min;

        return $this;
    }

    public function getMax(): ?int
    {
        return $this->max;
    }

    public function setMax(?int $max): static
    {
        $this->max = $max;

        return $this;
    }
}
