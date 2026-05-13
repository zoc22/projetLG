<?php
declare(strict_types=1);

namespace Modules\Authentication\Enums;

/**
 * Enum UserStatusEnum
 *
 * Définit les statuts possibles pour un compte utilisateur.
 * Permet de gérer l'activation, la suspension et le blocage.
 *
 * @package Modules\Authentication\Enums
 */
enum UserStatusEnum: string
{
    /**
     * Compte actif - L'utilisateur peut se connecter et utiliser le système
     */
    case ACTIF = 'actif';

    /**
     * Compte inactif - L'utilisateur a désactivé son compte ou n'a pas activé
     */
    case INACTIF = 'inactif';

    /**
     * Compte suspendu - Suspension temporaire par l'administrateur
     */
    case SUSPENDU = 'suspendu';

    /**
     * Compte bloqué - Bloqué définitivement pour non-respect des conditions
     */
    case BLOQUE = 'bloque';

    /**
     * En attente de vérification email
     */
    case EN_ATTENTE_VERIFICATION = 'en_attente_verification';

    /**
     * En attente d'approbation administrative
     */
    case EN_ATTENTE_APPROBATION = 'en_attente_approbation';

    /**
     * Vérifie si le compte peut se connecter
     *
     * @return bool
     */
    public function canLogin(): bool
    {
        return match($this) {
            self::ACTIF => true,
            default => false,
        };
    }

    /**
     * Vérifie si le compte est banni/bloqué
     *
     * @return bool
     */
    public function isBanned(): bool
    {
        return match($this) {
            self::BLOQUE => true,
            default => false,
        };
    }

    /**
     * Vérifie si le compte est en attente d'activation
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return match($this) {
            self::EN_ATTENTE_VERIFICATION, self::EN_ATTENTE_APPROBATION => true,
            default => false,
        };
    }

    /**
     * Obtient le label du statut
     *
     * @return string
     */
    public function getLabel(): string
    {
        return match($this) {
            self::ACTIF => 'Actif',
            self::INACTIF => 'Inactif',
            self::SUSPENDU => 'Suspendu',
            self::BLOQUE => 'Bloqué',
            self::EN_ATTENTE_VERIFICATION => 'En attente de vérification',
            self::EN_ATTENTE_APPROBATION => 'En attente d\'approbation',
        };
    }

    /**
     * Obtient la couleur associée au statut (pour l'affichage)
     *
     * @return string
     */
    public function getColor(): string
    {
        return match($this) {
            self::ACTIF => 'success',
            self::INACTIF => 'warning',
            self::SUSPENDU => 'warning',
            self::BLOQUE => 'danger',
            self::EN_ATTENTE_VERIFICATION, self::EN_ATTENTE_APPROBATION => 'info',
        };
    }
}