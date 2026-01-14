<?php

declare(strict_types=1);

namespace App\Tests\Inte;

use App\Repository\Necesse\WorldRepository;
use PHPUnit\Framework\Attributes as PU;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class FakeInteTest extends KernelTestCase
{
    #[PU\Test]
    public function fake(): void
    {
        self::bootKernel();
        $container = static::getContainer()->get(WorldRepository::class);
        $this->assertInstanceOf(WorldRepository::class, $container);
    }
}
