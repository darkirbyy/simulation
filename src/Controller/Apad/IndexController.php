<?php

declare(strict_types=1);

namespace App\Controller\Apad;

use App\Dto\Apad\Date;
use App\Form\Apad\DateType;
use App\Service\Apad\Solver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/apad', name: 'apad_')]
class IndexController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET', 'POST'])]
    public function index(Request $request, Solver $solver): Response
    {
        $date = Date::fromDateTime(new \DateTime());
        $form = $this->createForm(DateType::class, $date);
        $form->handleRequest($request);

        [$count, $board] = [null, null];
        if ($form->isSubmitted() && $form->isValid()) {
            [$count, $board] = $solver->solve($date);
        }

        return $this->render('apad/index.html.twig', [
            'form' => $form,
            'count' => $count,
            'board' => $board,
        ]);
    }
}
