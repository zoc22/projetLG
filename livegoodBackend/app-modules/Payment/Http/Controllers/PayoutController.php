<?php

namespace Modules\Payment\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Payment\Models\WithdrawalRequest;
use Modules\Payment\Enums\WithdrawalStatusEnum;
use Illuminate\Http\JsonResponse;

/**
 * Contrôleur administratif pour les paiements sortants.
 */
class PayoutController extends Controller
{
    /**
     * Voir toutes les demandes de retrait en attente d'approbation.
     */
    public function pendingApprovals(): JsonResponse
    {
        $pending = WithdrawalRequest::where('status', WithdrawalStatusEnum::REQUESTED)->get();
        return response()->json($pending);
    }

    /**
     * Approuver une demande pour le prochain batch de paiement hebdomadaire.
     */
    public function approve(string $id): JsonResponse
    {
        $request = WithdrawalRequest::findOrFail($id);
        $request->update(['status' => WithdrawalStatusEnum::APPROVED]);

        return response()->json(['message' => 'Demande approuvée pour le prochain cycle.']);
    }
}
