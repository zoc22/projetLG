<?php
declare(strict_types=1);

namespace Modules\Authentication\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Authentication\Services\AuthService;
use Modules\Authentication\Http\Requests\LoginRequest;
use Modules\Authentication\Http\Requests\RegisterRequest;
use Modules\Authentication\Http\Resources\UserResource;
use Modules\Authentication\Http\Resources\AuthResource;
use Modules\Authentication\DTO\LoginDTO;
use Modules\Authentication\DTO\RegisterDTO;

/**
 * Contrôleur AuthController
 *
 * Gère les endpoints d'authentification :
 * - Inscription
 * - Connexion
 * - Déconnexion
 * - Vérification email
 * - Rafraîchissement token
 *
 * @package Modules\Authentication\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * Service d'authentification
     *
     * @var AuthService
     */
    protected AuthService $authService;

    /**
     * Constructeur
     *
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Connexion utilisateur
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $dto = LoginDTO::fromRequest($request);
        $result = $this->authService->login($dto);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 401);
        }

        return response()->json(new AuthResource($result), 200);
    }

    /**
     * Inscription utilisateur
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        \Log::info('Register method called');
        try {
            \Log::info('Before DTO');
            $dto = RegisterDTO::fromRequest($request);
            \Log::info('After DTO');
            $result = $this->authService->register($dto);

            $statusCode = $result['success'] ? 201 : 422;

            return response()->json($result, $statusCode);
        } catch (\Exception $e) {
            \Log::error('Register error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Déconnexion utilisateur
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $result = $this->authService->logout($request->user());

        return response()->json($result, $result['success'] ? 200 : 500);
    }

    /**
     * Obtient l'utilisateur connecté
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json(new UserResource($request->user()), 200);
    }

    /**
     * Rafraîchit le token d'accès
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function refresh(Request $request): JsonResponse
    {
        $result = $this->authService->refreshToken($request);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 401);
        }

        return response()->json([
            'success' => true,
            'access_token' => $result['token'],
            'token_type' => 'Bearer',
            'expires_in' => config('authentication.jwt.ttl', 120) * 60,
        ], 200);
    }

    /**
     * Vérifie l'email de l'utilisateur
     *
     * @param string $id
     * @param string $hash
     * @return JsonResponse
     */
    public function verifyEmail(string $id, string $hash): JsonResponse
    {
        $result = $this->authService->verifyEmail($id, $hash);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Renvoie l'email de vérification
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function resendVerificationEmail(Request $request): JsonResponse
    {
        $result = $this->authService->resendVerificationEmail($request->user());

        return response()->json($result, $result['success'] ? 200 : 429);
    }
}
