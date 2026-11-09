<?php

declare(strict_types=1);

namespace App\Dto\Apad;

class Cell
{
    public function __construct(public string $text, public int $value, public array $borders) {}
}
