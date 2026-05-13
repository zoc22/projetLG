<?php
declare(strict_types=1);

namespace Modules\Core\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface RepositoryInterface
 *
 * Contrat standard pour tous les repositories.
 * Définit les opérations CRUD de base.
 *
 * @package Modules\Core\Contracts
 */
interface RepositoryInterface
{
    /**
     * Trouve un modèle par son ID
     *
     * @param string|int $id
     * @param array $relations
     * @return Model|null
     */
    public function find($id, array $relations = []): ?Model;

    /**
     * Trouve un modèle par son ID ou échoue
     *
     * @param string|int $id
     * @param array $relations
     * @return Model
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail($id, array $relations = []): Model;

    /**
     * Récupère tous les modèles
     *
     * @param array $criteria
     * @param array $relations
     * @return Collection
     */
    public function findAll(array $criteria = [], array $relations = []): Collection;

    /**
     * Récupère avec pagination
     *
     * @param int $perPage
     * @param array $criteria
     * @param array $relations
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $criteria = [], array $relations = []): LengthAwarePaginator;

    /**
     * Crée un nouveau modèle
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model;

    /**
     * Met à jour un modèle
     *
     * @param string|int $id
     * @param array $data
     * @return bool
     */
    public function update($id, array $data): bool;

    /**
     * Supprime un modèle
     *
     * @param string|int $id
     * @param bool $force
     * @return bool
     */
    public function delete($id, bool $force = false): bool;

    /**
     * Compte les enregistrements
     *
     * @param array $criteria
     * @return int
     */
    public function count(array $criteria = []): int;

    /**
     * Vérifie l'existence
     *
     * @param array $criteria
     * @return bool
     */
    public function exists(array $criteria): bool;

    /**
     * Démarre une transaction
     *
     * @return void
     */
    public function beginTransaction(): void;

    /**
     * Valide une transaction
     *
     * @return void
     */
    public function commit(): void;

    /**
     * Annule une transaction
     *
     * @return void
     */
    public function rollBack(): void;
}
