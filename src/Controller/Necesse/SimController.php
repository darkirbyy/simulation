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
use Symfony\Component\Form\FormError;
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

    #[Route('/new/{id?}', name: 'new', methods: ['GET', 'POST'], requirements: ['id' => Requirement::DIGITS])]
    public function new(?Sim $sim = null, Request $request, FormManager $fm, SimManager $simManager): Response
    {
        if (!is_null($sim)) {
            $sim = clone $sim;
        } else {
            $sim = new Sim();
            $sim->setDefaults();
        }

        $form = $this->createForm(SimType::class, $sim);
        $form->handleRequest($request);

        $flashSuccess = new FlashMessage('Nouvelle simulation executée avec succès.');
        if ($form->isSubmitted() && $form->isValid()) {
            $simManager->calculateBars($sim);

            if (!$fm->persist($sim, $flashSuccess)) {
                $form->addError(new FormError(''));
            } else {
                return $this->redirectToRoute('necesse_sim_show', ['id' => $sim->getId()]);
            }
        }

        return $this->render('necesse/sim/new.html.twig', [
            'sim' => $sim,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => Requirement::DIGITS])]
    public function show(Sim $sim, SimManager $simManager): Response
    {
        $fixedBars = $simManager->interpolateBars($sim, 101);

        return $this->render('necesse/sim/show.html.twig', [
            'sim' => $sim,
            'fixedBars' => $fixedBars,
        ]);
    }

    #[Route('/{id}/results', name: 'results', methods: ['GET'], requirements: ['id' => Requirement::DIGITS])]
    public function results(Sim $sim): Response
    {
        return $this->render('necesse/sim/_results.html.twig', [
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
