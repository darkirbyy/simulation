<?php

declare(strict_types=1);

namespace App\Controller\Necesse;

use App\Dto\Home\FlashMessage;
use App\Entity\Necesse\World;
use App\Form\Necesse\WorldType;
use App\Repository\Necesse\WorldRepository;
use App\Service\Home\FormManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/necesse/world', name: 'necesse_world_')]
class WorldController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(WorldRepository $worldRepo): Response
    {
        $worlds = $worldRepo->findAll();

        return $this->render('necesse/world/index.html.twig', [
            'worlds' => $worlds,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, FormManager $fm): Response
    {
        $world = new World();
        $world->setDefaults();

        $form = $this->createForm(WorldType::class, $world);
        $form->handleRequest($request);

        $flashSuccess = new FlashMessage('Nouveau monde ajouté avec succès.');
        if ($fm->validateAndPersist($form, $world, $flashSuccess)) {
            return $this->redirectToRoute('necesse_world_show', ['id' => $world->getId()]);
        }

        return $this->render('necesse/world/new.html.twig', [
            'world' => $world,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => Requirement::DIGITS])]
    public function show(World $world): Response
    {
        return $this->render('necesse/world/show.html.twig', [
            'world' => $world,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'], requirements: ['id' => Requirement::DIGITS])]
    public function delete(World $world, FormManager $fm): Response
    {
        $flashSuccess = new FlashMessage('Le monde a été supprimé avec succès.');
        if ($fm->checkTokenAndRemove('simulation/delete', $world, $flashSuccess)) {
            return $this->redirectToRoute('necesse_world_index');
        }

        return $this->redirectToRoute('necesse_world_show', ['id' => $world->getId()]);
    }
}
