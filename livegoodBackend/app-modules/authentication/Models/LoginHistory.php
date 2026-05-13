<?php
declare(strict_types=1);

namespace Modules\Authentication\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\HasUuid;

/**
 * Modèle LoginHistory
 *
 * Enregistre l'historique des connexions des utilisateurs.
 * Permet le suivi des activités suspectes et la sécurité.
 *
 * @package Modules\Authentication\Models
 */
class LoginHistory extends Model
{
    use HasUuid;

    /**
     * Nom de la table
     *
     * @var string
     */
    protected $table = 'login_histories';

    /**
     * Attributs assignables
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'ip_address', 'user_agent', 'login_at', 'logout_at',
        'login_successful', 'failure_reason', 'session_id',
    ];

    /**
     * Casts
     *
     * @var array
     */
    protected $casts = [
        'login_at' => 'datetime',
        'logout_at' => 'datetime',
        'login_successful' => 'boolean',
    ];

    /**
     * Relation avec l'utilisateur
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Marque la déconnexion
     *
     * @return void
     */
    public function markLogout(): void
    {
        $this->update(['logout_at' => now()]);
    }

    /**
     * Enregistre une tentative échouée
     *
     * @param int $userId
     * @param string $ip
     * @param string $reason
     * @return self
     */
    public static function recordFailedAttempt(int $userId, string $ip, string $reason): self
    {
        return self::create([
            'user_id' => $userId,
            'ip_address' => $ip,
            'user_agent' => request()->userAgent(),
            'login_at' => now(),
            'login_successful' => false,
            'failure_reason' => $reason,
        ]);
    }
}