<?php
declare(strict_types=1);

namespace Modules\Authentication\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Authentication\Services\AuthService;
use Modules\Authentication\Http\Requests\ForgotPasswordRequest;
use Modules\Authentication\Http\Requests\ResetPasswordRequest;
use Modules\Authentication\Http\Requests\ChangePasswordRequest;

/**
 * Contrôleur PasswordController
 *
 * Gère les endpoints liés aux mots de passe :
 * - Demande de réinitialisation
 * - Réinitialisation
 * - Changement de mot de passe (connecté)
 *
 * @package Modules\Authentication\Http\Controllers
 */
class PasswordController extends Controller
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
     * Demande de réinitialisation de mot de passe
     *
     * @param ForgotPasswordRequest $request
     * @return JsonResponse
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $result = $this->authService->forgotPassword($request->validated()['email']);

        // Toujours retourner 200 pour ne pas révéler l'existence d'un compte
        return response()->json([
            'success' => true,
            'message' => 'Si un compte existe avec cet email, vous recevrez un lien de réinitialisation.',
        ], 200);
    }

    /**
     * Réinitialisation du mot de passe
     *
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        
        $result = $this->authService->resetPassword(
            $data['email'],
            $data['token'],
            $data['password']
        );

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Changement de mot de passe (utilisateur connecté)
     *
     * @param ChangePasswordRequest $request
     * @return JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $result = $this->authService->changePassword(
            $request->user(),
            $request->validated()['current_password'],
            $request->validated()['new_password']
        );

        return response()->json($result, $result['success'] ? 200 : 400);
    }
}