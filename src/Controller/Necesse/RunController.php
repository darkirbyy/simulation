<?php

declare(strict_types=1);

namespace App\Controller\Necesse;

use App\Dto\Home\FlashMessage;
use App\Entity\Necesse\Run;
use App\Form\Necesse\RunType;
use App\Repository\Necesse\RunRepository;
use App\Service\Home\FormManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/necesse/run', name: 'necesse_run_')]
class RunController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(RunRepository $runRepository): Response
    {
        $runs = $runRepository->findAll();

        return $this->render('necesse/run/index.html.twig', [
            'runs' => $runs,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, FormManager $fm): Response
    {
        $run = new Run();
        $run->setDefaults();

        // A enlever et mettre les vrais valeurs
        $run->setDate(new \DateTime());
        $run->setDuration(10);

        $form = $this->createForm(RunType::class, $run);
        $form->handleRequest($request);

        $flashSuccess = new FlashMessage('Nouvelle simulation executée avec succès.');
        if ($fm->validateAndPersist($form, $run, $flashSuccess)) {
            return $this->redirectToRoute('necesse_run_index');
        }

        return $this->render('necesse/run/new.html.twig', [
            'run' => $run,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => Requirement::DIGITS])]
    public function show(Run $run): Response
    {
        return $this->render('necesse/run/show.html.twig', [
            'run' => $run,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'], requirements: ['id' => Requirement::DIGITS])]
    public function delete(Run $run, FormManager $fm): Response
    {
        $flashSuccess = new FlashMessage('La simulation a été supprimée avec succès.');
        if ($fm->checkTokenAndRemove('simulation/delete', $run, $flashSuccess)) {
            return $this->redirectToRoute('necesse_run_index');
        }

        return $this->redirectToRoute('necesse_run_show', ['id' => $run->getId()]);
    }
}
