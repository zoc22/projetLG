<?php
declare(strict_types=1);

namespace Modules\Core\Traits;

/**
 * Trait HasModule
 *
 * Permet à une classe de "savoir" à quel module elle appartient.
 * Utile pour les modèles, repositories, ou services qui doivent
 * connaître leur module parent.
 *
 * @package Modules\Core\Traits
 */
trait HasModule
{
    /**
     * Nom du module associé
     *
     * @var string|null
     */
    protected ?string $moduleName = null;

    /**
     * Définit le module associé
     *
     * @param string $moduleName
     * @return self
     */
    public function setModule(string $moduleName): self
    {
        $this->moduleName = $moduleName;
        return $this;
    }

    /**
     * Récupère le nom du module
     *
     * @return string|null
     */
    public function getModule(): ?string
    {
        return $this->moduleName;
    }

    /**
     * Récupère le chemin du module
     *
     * @param string $path
     * @return string
     */
    public function getModulePath(string $path = ''): string
    {
        if (!$this->moduleName) {
            return base_path($path);
        }

        $basePath = base_path("app-modules/{$this->moduleName}");

        if (!empty($path)) {
            $basePath = rtrim($basePath, '/') . '/' . ltrim($path, '/');
        }

        return $basePath;
    }

    /**
     * Récupère une configuration du module
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getModuleConfig(string $key, $default = null)
    {
        if (!$this->moduleName) {
            return $default;
        }

        return config("{$this->moduleName}.{$key}", $default);
    }

    /**
     * Vérifie si le module est activé
     *
     * @return bool
     */
    public function isModuleEnabled(): bool
    {
        if (!$this->moduleName) {
            return true;
        }

        return module_enabled($this->moduleName);
    }
}
