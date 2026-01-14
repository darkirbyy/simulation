<?php

declare(strict_types=1);

namespace App\Controller\Necesse;

use App\Dto\Home\FlashMessage;
use App\Entity\Necesse\World;
use App\Form\Necesse\WorldType;
use App\Service\Home\FormManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/necesse/world', name: 'necesse_world_')]
class WorldController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('necesse/world/index.html.twig', []);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, FormManager $fm): Response
    {
        $world = new World();
        $form = $this->createForm(WorldType::class, $world);
        $form->handleRequest($request);

        $flashSuccess = new FlashMessage('Nouveau paramètre du monde ajouté avec succès.');
        if ($fm->validateAndPersist($form, $world, $flashSuccess)) {
            return $this->redirectToRoute('necesse_world_index');
        }

        return $this->render('necesse/world/edit.html.twig', [
            'world' => $world,
            'form' => $form,
        ]);
    }
}
