<?php
declare(strict_types=1);

namespace Modules\Authentication\Enums;

/**
 * Enum UserRoleEnum
 *
 * Définit les rôles possibles pour les utilisateurs du système.
 * Chaque rôle a un niveau d'accès et des permissions associées.
 *
 * @package Modules\Authentication\Enums
 */
enum UserRoleEnum: string
{
    /**
     * Super Administrateur - Accès complet au système
     * Peut tout gérer : utilisateurs, configurations, modules
     */
    case SUPER_ADMIN = 'super_admin';

    /**
     * Administrateur - Gestion avancée
     * Peut gérer les utilisateurs, les rôles, et les configurations
     */
    case ADMIN = 'admin';

    /**
     * Support Client - Support uniquement
     * Accès aux tickets support et consultation des utilisateurs
     */
    case SUPPORT = 'support';

    /**
     * Affilié - Membre du réseau LiveGood
     * Accès à la généalogie, aux commissions, et aux outils de parrainage
     */
    case AFFILIATE = 'affiliate';

    /**
     * Membre simple - Client sans fonctionnalités réseau
     * Accès à la boutique et au profil
     */
    case MEMBER = 'member';

    /**
     * Invité - Utilisateur non authentifié
     */
    case GUEST = 'guest';

    /**
     * Obtient le niveau d'accès du rôle
     * Plus le niveau est élevé, plus les permissions sont étendues
     *
     * @return int
     */
    public function getAccessLevel(): int
    {
        return match($this) {
            self::SUPER_ADMIN => 100,
            self::ADMIN => 80,
            self::SUPPORT => 60,
            self::AFFILIATE => 40,
            self::MEMBER => 20,
            self::GUEST => 0,
        };
    }

    /**
     * Vérifie si le rôle a accès à une ressource
     *
     * @param UserRoleEnum $required
     * @return bool
     */
    public function hasAccessTo(UserRoleEnum $required): bool
    {
        return $this->getAccessLevel() >= $required->getAccessLevel();
    }

    /**
     * Obtient la liste des permissions par défaut pour ce rôle
     *
     * @return array
     */
    public function getDefaultPermissions(): array
    {
        return match($this) {
            self::SUPER_ADMIN => ['*'],
            self::ADMIN => [
                'users.*', 'roles.*', 'permissions.*',
                'modules.view', 'config.view', 'statistics.*', 'support.*',
            ],
            self::SUPPORT => ['users.view', 'support.*', 'statistics.view'],
            self::AFFILIATE => [
                'dashboard.view', 'genealogy.view', 'genealogy.matrix',
                'commissions.view', 'commissions.details', 'withdrawals.request',
                'websites.manage', 'profile.edit', 'referrals.view', 'statistics.personal',
            ],
            self::MEMBER => [
                'profile.view', 'profile.edit', 'shop.view',
                'shop.order', 'orders.history', 'support.create',
            ],
            self::GUEST => ['auth.login', 'auth.register', 'products.view', 'webinars.view'],
        };
    }

    /**
     * Obtient le label du rôle
     *
     * @return string
     */
    public function getLabel(): string
    {
        return match($this) {
            self::SUPER_ADMIN => 'Super Administrateur',
            self::ADMIN => 'Administrateur',
            self::SUPPORT => 'Support Client',
            self::AFFILIATE => 'Affilié',
            self::MEMBER => 'Membre',
            self::GUEST => 'Invité',
        };
    }
}