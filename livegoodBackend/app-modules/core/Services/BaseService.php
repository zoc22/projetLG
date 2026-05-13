<?php
declare(strict_types=1);

namespace Modules\Core\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Core\Contracts\RepositoryInterface;
use Modules\Core\Contracts\ServiceInterface;

/**
 * Classe BaseService
 *
 * Classe abstraite de base pour tous les services métier.
 * Fournit validation, gestion d'erreurs et transactions.
 *
 * @package Modules\Core\Services
 */
abstract class BaseService implements ServiceInterface
{
    /**
     * Repository associé
     *
     * @var RepositoryInterface|null
     */
    protected ?RepositoryInterface $repository = null;

    /**
     * Liste des erreurs
     *
     * @var array
     */
    protected array $errors = [];

    /**
     * Constructeur
     *
     * @param RepositoryInterface|null $repository
     */
    public function __construct(?RepositoryInterface $repository = null)
    {
        $this->repository = $repository;
    }

    /**
     * Exécute le service
     *
     * @param array $data
     * @return mixed
     */
    abstract public function execute(array $data);

    /**
     * Valide les données
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @return bool
     * @throws ValidationException
     */
    public function validate(array $data, array $rules = [], array $messages = []): bool
    {
        if (empty($rules) && method_exists($this, 'rules')) {
            $rules = $this->rules();
        }

        if (empty($rules)) {
            return true;
        }

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            $this->errors = $validator->errors()->toArray();
            throw new ValidationException($validator);
        }

        return true;
    }

    /**
     * Formate la réponse
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return array
     */
    public function respond($data, string $message = 'Success', int $code = 200): array
    {
        return [
            'success' => $code >= 200 && $code < 300,
            'message' => $message,
            'code' => $code,
            'data' => $data,
            'errors' => $this->errors,
            'timestamp' => now()->toIso8601String(),
        ];
    }

    /**
     * Récupère les erreurs
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Vérifie si des erreurs existent
     *
     * @return bool
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Démarre une transaction
     *
     * @return void
     */
    protected function beginTransaction(): void
    {
        if ($this->repository) {
            $this->repository->beginTransaction();
        } else {
            DB::beginTransaction();
        }
    }

    /**
     * Valide une transaction
     *
     * @return void
     */
    protected function commit(): void
    {
        if ($this->repository) {
            $this->repository->commit();
        } else {
            DB::commit();
        }
    }

    /**
     * Annule une transaction
     *
     * @return void
     */
    protected function rollBack(): void
    {
        if ($this->repository) {
            $this->repository->rollBack();
        } else {
            DB::rollBack();
        }
    }

    /**
     * Exécute dans une transaction
     *
     * @param callable $callback
     * @return mixed
     * @throws \Throwable
     */
    protected function transaction(callable $callback)
    {
        $this->beginTransaction();

        try {
            $result = $callback();
            $this->commit();
            return $result;
        } catch (\Throwable $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * Ajoute une erreur
     *
     * @param string $field
     * @param string $message
     * @return void
     */
    protected function addError(string $field, string $message): void
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }

        $this->errors[$field][] = $message;
    }

    /**
     * Nettoie les erreurs
     *
     * @return void
     */
    protected function clearErrors(): void
    {
        $this->errors = [];
    }
}
