<?php

declare(strict_types=1);

namespace App\Dto\Necesse\SelfManaged;

use App\Enum\Necesse\SexEnum;
use App\Enum\Necesse\TypeEnum;

/**
 * Each living being in the simulation must derived from this class.
 */
abstract class LivingBeing
{
    /**
     * All living beings manage by themselves adding,filtering or removing other elements of the henhouse.
     *
     * @param Henhouse $henhouse the henhouse containing all the sim parameters the randomizer
     */
    public function __construct(protected Henhouse $henhouse)
    {
    }

    /**
     * Called once when the living being is created.
     *
     * @param [type] ...$args  any specific arguments to initialize the living being
     */
    abstract public function initialize(...$args): void;

    /**
     * Called to advance the simulation of $deltaTime steps.
     */
    abstract public function tick(int $deltaTime): void;

    /**
     * Determine the sex of the living being.
     */
    abstract public function getSex(): SexEnum;

    /**
     * Determine the type of the living being.
     */
    abstract public function getType(): TypeEnum;

    /**
     * Determine how much iteration between something will change for this libing being.
     */
    abstract public function getTimer(): int;
}
