<?php

namespace Modules\Payment\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Payment\Services\WithdrawalService;
use Modules\Payment\Http\Requests\WithdrawalRequest as WebRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * Contrôleur pour les opérations de retrait.
 */
class WithdrawalController extends Controller
{
    public function __construct(protected WithdrawalService $withdrawalService) {}

    /**
     * Créer une demande de retrait.
     */
    public function store(WebRequest $request): JsonResponse
    {
        try {
            $withdrawal = $this->withdrawalService->requestWithdrawal(
                Auth::id(),
                $request->amount,
                $request->payment_method_id
            );

            return response()->json([
                'success' => true,
                'data' => $withdrawal
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Historique des retraits de l'utilisateur.
     */
    public function index(): JsonResponse
    {
        $requests = \Modules\Payment\Models\WithdrawalRequest::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($requests);
    }
}
