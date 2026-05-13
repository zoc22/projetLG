<?php
declare(strict_types=1);

namespace Modules\Core\Contracts;

/**
 * Interface ModuleInterface
 *
 * Contrat standard que tous les modules doivent implémenter.
 * Garantit une structure uniforme pour la découverte et la gestion des modules.
 *
 * @package Modules\Core\Contracts
 */
interface ModuleInterface
{
    /**
     * Exécuté après l'enregistrement de tous les services
     * Utilisé pour charger les routes, vues, migrations
     *
     * @return void
     */
    public function boot(): void;

    /**
     * Exécuté pendant l'enregistrement du module
     * Utilisé pour lier les classes dans le conteneur
     *
     * @return void
     */
    public function register(): void;

    /**
     * Obtient le nom du module
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Obtient la version du module
     *
     * @return string
     */
    public function getVersion(): string;

    /**
     * Vérifie si le module est activé
     *
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * Obtient la liste des dépendances
     *
     * @return array
     */
    public function getDependencies(): array;

    /**
     * Vérifie les dépendances
     *
     * @return bool
     */
    public function checkDependencies(): bool;

    /**
     * Exécute les migrations
     *
     * @return void
     */
    public function runMigrations(): void;

    /**
     * Publie les assets
     *
     * @return void
     */
    public function publishAssets(): void;
}
