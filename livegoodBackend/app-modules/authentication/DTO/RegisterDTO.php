<?php
declare(strict_types=1);

namespace Modules\Authentication\DTO;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;
use Modules\Authentication\Enums\UserStatusEnum;
use Modules\Authentication\Enums\UserRoleEnum;

/**
 * DTO RegisterDTO
 *
 * Transporte les données d'inscription entre les couches de l'application.
 * Inclut les champs personnels, le parrainage et les préférences utilisateur.
 *
 * @package Modules\Authentication\DTO
 */
class RegisterDTO
{
    /**
     * Nom de famille
     *
     * @var string
     */
    public string $nom;

    /**
     * Prénom
     *
     * @var string
     */
    public string $prenom;

    /**
     * Adresse email
     *
     * @var string
     */
    public string $email;

    /**
     * Mot de passe (en clair)
     *
     * @var string
     */
    public string $password;

    /**
     * Confirmation du mot de passe
     *
     * @var string
     */
    public string $passwordConfirmation;

    /**
     * Pays de résidence
     *
     * @var string|null
     */
    public ?string $pays;

    /**
     * Devise préférée
     *
     * @var string
     */
    public string $devise;

    /**
     * Code de parrainage (optionnel)
     *
     * @var string|null
     */
    public ?string $parrainCode;

    /**
     * Rôle de l'utilisateur (défaut: membre)
     *
     * @var string
     */
    public string $role;

    /**
     * Type d'abonnement (mensuel ou annuel)
     *
     * @var string
     */
    public string $subscriptionType;

    /**
     * Acceptation des conditions générales
     *
     * @var bool
     */
    public bool $acceptTerms;

    /**
     * Acceptation de la newsletter
     *
     * @var bool
     */
    public bool $acceptNewsletter;

    /**
     * Adresse IP d'inscription
     *
     * @var string|null
     */
    public ?string $ipAddress;

    /**
     * User agent
     *
     * @var string|null
     */
    public ?string $userAgent;

    /**
     * Constructeur privé
     */
    private function __construct(array $data)
    {
        $this->nom = trim($data['nom']);
        $this->prenom = trim($data['prenom']);
        $this->email = strtolower(trim($data['email']));
        $this->password = $data['password'];
        $this->passwordConfirmation = $data['password_confirmation'];
        $this->pays = $data['pays'] ?? null;
        $this->devise = $data['devise'] ?? 'USD';
        $this->parrainCode = $data['parrain_code'] ?? null;
        $this->role = $data['role'] ?? 'member';
        $this->subscriptionType = $data['subscription_type'] ?? 'mensuel';
        $this->acceptTerms = $data['accept_terms'] ?? false;
        $this->acceptNewsletter = $data['accept_newsletter'] ?? false;
        $this->ipAddress = $data['ip_address'] ?? null;
        $this->userAgent = $data['user_agent'] ?? null;
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
            'nom' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZÀ-ÿ\s\'-]+$/'],
            'prenom' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZÀ-ÿ\s\'-]+$/'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
            'password_confirmation' => ['required', 'string'],
            'pays' => ['nullable', 'string', 'max:100'],
            'devise' => ['nullable', 'string', 'in:USD,EUR,GBP,CAD'],
            'parrain_code' => ['nullable', 'string', 'exists:affiliates,code_affiliation'],
            'role' => ['nullable', 'string', 'in:affiliate,member'],
            'subscription_type' => ['nullable', 'string', 'in:mensuel,annuel'],
            'accept_terms' => ['required', 'accepted'],
            'accept_newsletter' => ['sometimes', 'boolean'],
        ]);

        $validated['ip_address'] = $request->ip();
        $validated['user_agent'] = $request->userAgent();

        return new self($validated);
    }

    /**
     * Crée un DTO à partir d'un tableau
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    /**
     * Convertit le DTO en tableau pour la création de l'utilisateur
     *
     * @return array
     */
    public function toUserArray(): array
    {
        return [
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
            'password' => $this->password,
            'pays' => $this->pays,
            'devise' => $this->devise,
            'type_utilisateur' => $this->role,
            'statut_compte' => UserStatusEnum::EN_ATTENTE_VERIFICATION->value,
            'date_inscription' => now(),
            'ip_inscription' => $this->ipAddress,
            'avatar' => null,
            'google_id' => null,
            'facebook_id' => null,
            'github_id' => null,
            'login_attempts' => 0,
        ];
    }

    /**
     * Vérifie si l'utilisateur souhaite devenir affilié
     *
     * @return bool
     */
    public function wantsToBeAffiliate(): bool
    {
        return $this->role === 'affiliate';
    }

    /**
     * Vérifie si l'utilisateur a un code de parrainage valide
     *
     * @return bool
     */
    public function hasReferralCode(): bool
    {
        return !empty($this->parrainCode);
    }
}