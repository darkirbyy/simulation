<?php

declare(strict_types=1);

namespace App\Service\Home;

use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Exception;
use Psr\Log\LoggerInterface;

class ExceptionManager
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * Log catched exception to keep trace.
     */
    public function handle(string $method, string $message, array $params = []): void
    {
        $this->logger->$method($message, $params);
    }

    /**
     * Transform a constraint exception into a user message.
     */
    public function handleDatabase(\Exception $exception): string
    {
        $this->handle('warning', $exception->getMessage());
        if ($exception instanceof ForeignKeyConstraintViolationException) {
            $message = "Impossible de supprimer cet élément tant que d'autres éléments y font référence.";
        } elseif ($exception instanceof NotNullConstraintViolationException) {
            $message = "Impossible d'ajouter ou de modifier cet élément car certains champs requis sont vides.";
        } elseif ($exception instanceof UniqueConstraintViolationException) {
            $message = "Impossible d'ajouter ou de modifier cet élément car certains champs doivent être uniques.";
        } else {
            $message = "Erreur lors de l'ajout, de la modification ou de la suppression de cet élément.";
        }

        return $message;
    }
}
