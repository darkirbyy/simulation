<?php

declare(strict_types=1);

namespace App\Controller\Necesse;

use App\Dto\Home\FlashMessage;
use App\Entity\Necesse\Sim;
use App\Form\Necesse\SimType;
use App\Repository\Necesse\SimRepository;
use App\Service\Home\FormManager;
use App\Service\Necesse\SimManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/necesse/sim', name: 'necesse_sim_')]
class SimController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(SimRepository $simRepository): Response
    {
        $sims = $simRepository->findAll();

        return $this->render('necesse/sim/index.html.twig', [
            'sims' => $sims,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, FormManager $fm, SimManager $simManager): Response
    {
        $sim = new Sim();
        $sim->setDefaults();

        $form = $this->createForm(SimType::class, $sim);
        $form->handleRequest($request);

        $flashSuccess = new FlashMessage('Nouvelle simulation executée avec succès.');
        if ($form->isSubmitted() && $form->isValid()) {
            $simManager->calculateBars($sim);
            $fm->persist($sim, $flashSuccess);

            return $this->redirectToRoute('necesse_sim_index');
        }

        return $this->render('necesse/sim/new.html.twig', [
            'sim' => $sim,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => Requirement::DIGITS])]
    public function show(Sim $sim): Response
    {
        return $this->render('necesse/sim/show.html.twig', [
            'sim' => $sim,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'], requirements: ['id' => Requirement::DIGITS])]
    public function delete(Sim $sim, FormManager $fm): Response
    {
        $flashSuccess = new FlashMessage('La simulation a été supprimée avec succès.');
        if ($fm->checkTokenAndRemove('simulation/delete', $sim, $flashSuccess)) {
            return $this->redirectToRoute('necesse_sim_index');
        }

        return $this->redirectToRoute('necesse_sim_show', ['id' => $sim->getId()]);
    }
}
