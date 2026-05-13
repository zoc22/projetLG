<?php
declare(strict_types=1);

namespace Modules\Authentication\DTO;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * DTO LoginDTO
 *
 * Transporte les données de connexion entre les couches de l'application.
 * Assure la validation et le typage des données d'authentification.
 *
 * @package Modules\Authentication\DTO
 */
class LoginDTO
{
    /**
     * Adresse email de l'utilisateur
     *
     * @var string
     */
    public string $email;

    /**
     * Mot de passe de l'utilisateur (en clair)
     *
     * @var string
     */
    public string $password;

    /**
     * Indique si l'utilisateur souhaite rester connecté
     *
     * @var bool
     */
    public bool $remember;

    /**
     * Adresse IP de la requête
     *
     * @var string|null
     */
    public ?string $ipAddress;

    /**
     * User agent du navigateur
     *
     * @var string|null
     */
    public ?string $userAgent;

    /**
     * Constructeur privé - Utiliser les méthodes statiques
     *
     * @param string $email
     * @param string $password
     * @param bool $remember
     * @param string|null $ipAddress
     * @param string|null $userAgent
     */
    private function __construct(
        string $email,
        string $password,
        bool $remember = false,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ) {
        $this->email = strtolower(trim($email));
        $this->password = $password;
        $this->remember = $remember;
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
    }

    /**
     * Crée un DTO à partir d'une requête HTTP
     *
     * @param Request $request
     * @return self
     * @throws ValidationException
     */
    public static function fromRequest(Request $request): self
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
            'remember' => ['sometimes', 'boolean'],
        ]);

        return new self(
            email: $validated['email'],
            password: $validated['password'],
            remember: $validated['remember'] ?? false,
            ipAddress: $request->ip(),
            userAgent: $request->userAgent()
        );
    }

    /**
     * Crée un DTO à partir d'un tableau de données
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'],
            password: $data['password'],
            remember: $data['remember'] ?? false,
            ipAddress: $data['ip_address'] ?? null,
            userAgent: $data['user_agent'] ?? null
        );
    }

    /**
     * Convertit le DTO en tableau
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
            'remember' => $this->remember,
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent,
        ];
    }
}