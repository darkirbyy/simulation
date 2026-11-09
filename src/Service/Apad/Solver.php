<?php

declare(strict_types=1);

namespace App\Service\Apad;

use App\Dto\Apad\Cell;
use App\Dto\Apad\Date;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

class Solver
{
    // prettier-ignore
    public const array TEXTS = [
        'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'f1',
        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'f2',
        '1', '2', '3', '4', '5', '6', '7',
        '8', '9', '10', '11', '12', '13', '14',
        '15', '16', '17', '18', '19', '20', '21',
        '22', '23', '24', '25', '26', '27', '28',
        '29', '30', '31', 'f3', 'f4', 'f5', 'f6', ];

    // prettier-ignore
    public const array DEFAULT = [
        0,  0,  0,  0,  0,  0,  10,
        0,  0,  0,  0,  0,  0,  10,
        0,  0,  0,  0,  0,  0,  0,
        0,  0,  0,  0,  0,  0,  0,
        0,  0,  0,  0,  0,  0,  0,
        0,  0,  0,  0,  0,  0,  0,
        0,  0,  0,  10, 10, 10, 10];

    public function __construct(private Filesystem $filesystem, #[Autowire('%kernel.project_dir%')] private string $projectDir) {}

    public function solve(Date $date): array
    {
        $solutions = $this->readFile($date);
        if (false === $solutions) {
            $solutions = $this->callRustBin($date);
            if (false !== $solutions) {
                $this->writeFile($date, $solutions);
            }
        }

        if (false === $solutions) {
            $count = 0;
            $values = self::DEFAULT;
        } else {
            $count = count($solutions);
            $values = explode(';', $solutions[random_int(0, count($solutions) - 1)]);
            array_walk($values, fn(&$value) => ($value = (int) $value));
        }

        $board = [];
        for ($index = 0; $index < count(self::TEXTS); $index++) {
            $text = self::TEXTS[$index];
            $value = $values[$index];
            $borders = [];
            foreach ([-7 => 'bt', -1 => 'bl', 1 => 'br', 7 => 'bb'] as $offset => $class) {
                if (!isset($values[$index + $offset]) || $values[$index + $offset] != $value) {
                    $borders[] = $class;
                }
            }
            $board[$index] = new Cell($text, $value, $borders);
        }

        return [$count, $board];
    }

    private function callRustBin(Date $date): array|false
    {
        $binPath = Path::join($this->projectDir, 'bin', 'apad_solver');
        $command = $binPath . ' ' . $date->toCommand();

        $output = null;
        $resultCode = null;
        exec($command, $output, $resultCode);

        return 0 == $resultCode ? $output : false;
    }

    private function readFile(Date $date): array|false
    {
        $path = $this->buildPath($date);
        if (!$this->filesystem->exists($path)) {
            return false;
        }

        return explode("\n", $this->filesystem->readFile($path));
    }

    private function writeFile(Date $date, array $solutions): void
    {
        $path = $this->buildPath($date);
        $this->filesystem->dumpFile($path, implode("\n", $solutions));
    }

    private function buildPath(Date $date): string
    {
        return Path::join($this->projectDir, 'var', 'apad', $date->toFileName());
    }
}
