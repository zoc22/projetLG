<?php
declare(strict_types=1);

namespace Modules\Core\Traits;

use Illuminate\Support\Str;

/**
 * Trait HasUuid
 *
 * Ajoute automatiquement un UUID à un modèle Eloquent.
 * Remplace l'auto-incrément standard par un UUID v4.
 *
 * Ce trait ajoute les fonctionnalités suivantes:
 * - Génération automatique d'UUID lors de la création d'un modèle
 * - Désactivation de l'auto-incrément
 * - Utilisation de champ de clé primaire UUID
 *
 * @package Modules\Core\Traits
 * @mixin \Illuminate\Database\Eloquent\Model
 * @method mixed getKeyName()
 * @method mixed getKey()
 * @method static void creating(\Closure $callback)
 */
trait HasUuid
{
    /**
     * Boot du trait - Configuration automatique lors de la création
     */
    protected static function bootHasUuid(): void
    {
        static::creating(function ($model) {
            // Génère un UUID si la clé primaire est vide
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Désactive l'auto-incrément
     *
     * @return bool
     */
    public function getIncrementing(): bool
    {
        return false;
    }

    /**
     * Définit le type de la clé primaire
     *
     * @return string
     */
    public function getKeyType(): string
    {
        return 'string';
    }

    /**
     * Récupère l'UUID du modèle
     *
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->getKey();
    }
}
