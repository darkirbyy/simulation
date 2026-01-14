<?php

declare(strict_types=1);

namespace App\Controller\Home;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

/**
 * Controller for managing the error page.
 */
class ErrorController extends AbstractController
{
    /**
     * Render and return the response page + add meta tag for turbo to full reload if the status code is not 422.
     */
    public function show(Request $request, FlattenException $exception, ?DebugLoggerInterface $logger = null): Response
    {
        $statusCode = $exception->getStatusCode();

        $turboForceReload = $request->headers->has('Turbo-Frame') && 422 != $statusCode;

        $response = $this->render('home/error.html.twig', [
            'turbo_force_reload' => $turboForceReload,
            'error_code' => $statusCode,
        ]);

        return new Response($response->getContent(), $statusCode, $response->headers->all());
    }
}
