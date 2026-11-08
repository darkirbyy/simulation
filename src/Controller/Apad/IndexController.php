<?php

declare(strict_types=1);

namespace App\Controller\Apad;

use App\Dto\Apad\Date;
use App\Enum\Apad\MonthEnum;
use App\Service\Apad\Solver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/apad', name: 'apad_')]
class IndexController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(Solver $solver): Response
    {
        [$ok, $board] = $solver->solve(new Date(31, MonthEnum::Dec));

        return $this->render('apad/index.html.twig', [
            'ok' => $ok,
            'board' => $board,
        ]);
    }
}
