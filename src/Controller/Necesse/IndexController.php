<?php

declare(strict_types=1);

namespace App\Controller\Necesse;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/necesse', name: 'necesse_')]
class IndexController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('necesse/index.html.twig', []);
    }
}
