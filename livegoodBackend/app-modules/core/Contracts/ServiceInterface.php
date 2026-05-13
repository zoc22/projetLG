<?php
declare(strict_types=1);

namespace Modules\Core\Contracts;

/**
 * Interface ServiceInterface
 *
 * Contrat standard pour tous les services métier.
 * Un service contient la logique métier et orchestre les repositories.
 *
 * @package Modules\Core\Contracts
 */
interface ServiceInterface
{
    /**
     * Exécute l'opération métier
     *
     * @param array $data
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function execute(array $data);

    /**
     * Valide les données
     *
     * @param array $data
     * @return bool
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validate(array $data): bool;

    /**
     * Formate la réponse
     *
     * @param mixed $result
     * @param string $message
     * @param int $code
     * @return array
     */
    public function respond($result, string $message = 'Success', int $code = 200): array;

    /**
     * Récupère les erreurs
     *
     * @return array
     */
    public function getErrors(): array;

    /**
     * Vérifie si des erreurs existent
     *
     * @return bool
     */
    public function hasErrors(): bool;
}
