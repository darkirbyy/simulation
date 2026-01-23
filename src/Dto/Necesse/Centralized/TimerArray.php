<?php

declare(strict_types=1);

namespace App\Dto\Necesse\Centralized;

class TimerArray
{
    public int $active;
    public \SplFixedArray $fixedArray;

    public function __construct(int $size)
    {
        // A timer is active if it is above 0, and active keep the count for fastest conversion to bars
        $this->fixedArray = new \SplFixedArray($size);
        $this->active = 0;

        // Initiliaze all timers to disable, meaning -1
        for ($i = 0; $i < $this->fixedArray->getSize(); $i++) {
            $this->fixedArray[$i] = -1;
        }
    }

    public function tickTimers(int $deltaTime): void
    {
        // Remove deltaTime from each timer active
        for ($i = 0; $i < $this->fixedArray->getSize(); $i++) {
            if ($this->fixedArray[$i] >= 0) {
                $this->fixedArray[$i] -= $deltaTime;
            }
        }
    }

    public function minTimers(): int
    {
        // Search for the min of the timers above 0
        $min = PHP_INT_MAX;
        for ($i = 0; $i < $this->fixedArray->getSize(); $i++) {
            if ($this->fixedArray[$i] >= 0) {
                $min = min($min, $this->fixedArray[$i]);
            }
        }

        return $min;
    }

    public function addTimer(int $timer): bool
    {
        // Create a new timer if there is enough place in the fixed array and return true
        for ($i = 0; $i < $this->fixedArray->getSize(); $i++) {
            if (-1 == $this->fixedArray[$i]) {
                $this->fixedArray[$i] = $timer;
                $this->active++;

                return true;
            }
        }

        // Return false otherwise
        return false;
    }

    public function removeTimer(int $i): void
    {
        // Remove a timer by its index (by setting it to -1)
        $this->fixedArray[$i] = -1;
        $this->active--;
    }
}
