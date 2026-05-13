<?php
declare(strict_types=1);

namespace Modules\Authentication\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\HasUuid;

/**
 * Modèle PasswordReset
 *
 * Gère les tokens de réinitialisation de mot de passe.
 * Assure la sécurité et l'expiration des liens de réinitialisation.
 *
 * @package Modules\Authentication\Models
 */
class PasswordReset extends Model
{
    use HasUuid;

    /**
     * Désactive les timestamps automatiques
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Nom de la table
     *
     * @var string
     */
    protected $table = 'password_resets';

    /**
     * Attributs assignables
     *
     * @var array
     */
    protected $fillable = [
        'email', 'token', 'created_at', 'expires_at',
    ];

    /**
     * Casts
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Vérifie si le token a expiré
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Supprime les tokens expirés
     *
     * @return int
     */
    public static function deleteExpired(): int
    {
        return self::where('expires_at', '<', now())->delete();
    }
}