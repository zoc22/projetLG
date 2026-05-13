<?php
declare(strict_types=1);

namespace Modules\Authentication\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Authentication\Services\TwoFactorService;

/**
 * Contrôleur TwoFactorController
 *
 * Gère l'authentification à deux facteurs :
 * - Activation
 * - Confirmation
 * - Désactivation
 * - Codes de récupération
 *
 * @package Modules\Authentication\Http\Controllers
 */
class TwoFactorController extends Controller
{
    /**
     * Service 2FA
     *
     * @var TwoFactorService
     */
    protected TwoFactorService $twoFactorService;

    /**
     * Constructeur
     *
     * @param TwoFactorService $twoFactorService
     */
    public function __construct(TwoFactorService $twoFactorService)
    {
        $this->twoFactorService = $twoFactorService;
    }

    /**
     * Active la 2FA
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function enable(Request $request): JsonResponse
    {
        $result = $this->twoFactorService->enable($request->user());

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Confirme l'activation de la 2FA
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function confirm(Request $request): JsonResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $result = $this->twoFactorService->confirm(
            $request->user(),
            $request->input('code')
        );

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Désactive la 2FA
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function disable(Request $request): JsonResponse
    {
        $result = $this->twoFactorService->disable($request->user());

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Obtient les codes de récupération
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getRecoveryCodes(Request $request): JsonResponse
    {
        $codes = $this->twoFactorService->getRecoveryCodes($request->user());

        return response()->json([
            'success' => true,
            'recovery_codes' => $codes,
        ], 200);
    }

    /**
     * Régénère les codes de récupération
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function regenerateRecoveryCodes(Request $request): JsonResponse
    {
        $result = $this->twoFactorService->regenerateRecoveryCodes($request->user());

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Vérifie le code 2FA (challenge)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $result = $this->twoFactorService->verifyChallenge(
            $request->input('code'),
            $request->input('remember', false)
        );

        return response()->json($result, $result['success'] ? 200 : 401);
    }
}