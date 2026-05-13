<?php
declare(strict_types=1);

namespace Modules\Authentication\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Core\Traits\HasUuid;
use Modules\Core\Traits\HasPermissions;
use Modules\Authentication\Enums\UserRoleEnum;
use Modules\Authentication\Enums\UserStatusEnum;
use Modules\Affiliation\Models\Affiliate;
use Modules\Affiliation\Models\Subscription;
use Modules\Support\Models\SupportTicket;

/**
 * Modèle User
 *
 * Représente un utilisateur du système LiveGood.
 * Gère l'authentification, les relations avec les affiliés,
 * l'historique des connexions, et les tickets support.
 *
 * @package Modules\Authentication\Models
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasUuid, HasPermissions;

    /**
     * Nom de la table associée
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Clé primaire non auto-incrémentée (UUID)
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Type de la clé primaire
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Désactive l'auto-incrément
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Attributs assignables en masse
     *
     * @var array
     */
    protected $fillable = [
        'id', 'nom', 'prenom', 'email', 'password', 'pays', 'devise',
        'statut_compte', 'type_utilisateur', 'email_verified_at',
        'date_inscription', 'ip_inscription', 'last_login_at', 'last_login_ip',
        'two_factor_secret', 'two_factor_recovery_codes', 'two_factor_confirmed_at',
        'remember_token',
    ];

    /**
     * Attributs cachés pour la sérialisation
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes',
    ];

    /**
     * Casts d'attributs
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_inscription' => 'datetime',
        'last_login_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
        'statut_compte' => UserStatusEnum::class,
        'type_utilisateur' => UserRoleEnum::class,
    ];

    /**
     * Dates à traiter comme des instances Carbon
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    /**
     * Relation avec l'affilié
     * Un utilisateur peut être un affilié (un à un)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function affiliate(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Affiliate::class, 'user_id');
    }

    /**
     * Relation avec la souscription
     * Un utilisateur a une souscription
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function subscription(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Subscription::class, 'user_id');
    }

    /**
     * Relation avec l'historique des connexions
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function loginHistory(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LoginHistory::class);
    }

    /**
     * Relation avec les tickets support
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function supportTickets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    /**
     * Vérifie si l'utilisateur peut se connecter
     *
     * @return bool
     */
    public function canLogin(): bool
    {
        // Vérifier le statut du compte
        if (!$this->statut_compte->canLogin()) {
            return false;
        }

        // Vérifier si l'email est vérifié si requis
        if (config('authentication.security.force_email_verification') && !$this->hasVerifiedEmail()) {
            return false;
        }

        // Vérifier si l'utilisateur n'est pas banni
        if ($this->statut_compte->isBanned()) {
            return false;
        }

        return true;
    }

    /**
     * Vérifie si l'email est vérifié
     *
     * @return bool
     */
    public function hasVerifiedEmail(): bool
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * Marque l'email comme vérifié
     *
     * @return bool
     */
    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
            'statut_compte' => UserStatusEnum::ACTIF,
        ])->save();
    }

    /**
     * Vérifie si l'utilisateur est super administrateur
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->type_utilisateur === UserRoleEnum::SUPER_ADMIN;
    }

    /**
     * Vérifie si l'utilisateur est administrateur
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return in_array($this->type_utilisateur, [
            UserRoleEnum::SUPER_ADMIN,
            UserRoleEnum::ADMIN,
        ]);
    }

    /**
     * Vérifie si l'utilisateur est affilié
     *
     * @return bool
     */
    public function isAffiliate(): bool
    {
        return $this->type_utilisateur === UserRoleEnum::AFFILIATE;
    }

    /**
     * Obtient le nom complet de l'utilisateur
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    /**
     * Met à jour la dernière connexion
     *
     * @param string $ip
     * @return void
     */
    public function updateLastLogin(string $ip): void
    {
        $this->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => $ip,
        ])->save();

        // Journaliser la connexion
        $this->loginHistory()->create([
            'ip_address' => $ip,
            'user_agent' => request()->userAgent(),
            'login_at' => now(),
            'login_successful' => true,
        ]);
    }

    /**
     * Incrémente le compteur de tentatives de connexion
     *
     * @return void
     */
    public function incrementLoginAttempts(): void
    {
        $this->forceFill([
            'login_attempts' => $this->login_attempts + 1,
        ])->save();
    }

    /**
     * Réinitialise le compteur de tentatives de connexion
     *
     * @return void
     */
    public function resetLoginAttempts(): void
    {
        $this->forceFill([
            'login_attempts' => 0,
        ])->save();
    }

    /**
     * Vérifie si le compte est verrouillé
     *
     * @return bool
     * @throws \Illuminate\Contracts\Cache\LockTimeoutException
     */
    public function isLocked(): bool
    {
        $maxAttempts = config('authentication.security.max_login_attempts', 5);
        $lockoutTime = config('authentication.security.lockout_time', 15);

        if ($this->login_attempts >= $maxAttempts) {
            $lastAttempt = $this->loginHistory()
                ->where('login_successful', false)
                ->latest()
                ->first();

            if ($lastAttempt && $lastAttempt->login_at->diffInMinutes(now()) < $lockoutTime) {
                return true;
            }

            $this->resetLoginAttempts();
        }

        return false;
    }

    /**
     * Obtient l'ID unique pour le cache
     *
     * @return string
     */
    public function getCacheKey(): string
    {
        return 'user_' . $this->getKey();
    }

    /**
     * Scope pour les utilisateurs actifs
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('statut_compte', UserStatusEnum::ACTIF->value);
    }

    /**
     * Scope par rôle
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param UserRoleEnum $role
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByRole($query, UserRoleEnum $role)
    {
        return $query->where('type_utilisateur', $role->value);
    }
}