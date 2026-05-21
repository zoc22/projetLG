<?php

namespace Modules\Genealogy\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Genealogy\Services\MatrixService;
use Modules\Genealogy\Services\TreeBuilderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * API pour gérer la vue matricielle et les bonus Powerline.
 */
class MatrixController extends Controller
{
    public function __construct(
        protected MatrixService $matrixService,
        protected TreeBuilderService $treeService
    ) {}

    /**
     * Voir mon arbre matriciel visuel (profondeur limitée pour le front).
     */
    public function viewTree(): JsonResponse
    {
        $tree = $this->treeService->buildMatrixTree(Auth::id(), 4);

        return response()->json([
            'status' => 'success',
            'tree'   => $tree
        ]);
    }

    /**
     * Consulter mon estimation de bonus matriciel actuel.
     */
    public function currentBonus(): JsonResponse
    {
        $bonus = $this->matrixService->calculateUserBonus(Auth::id());

        return response()->json([
            'status' => 'success',
            'amount' => $bonus,
            'currency' => 'USD'
        ]);
    }

    /**
     * Obtenir les détails d'un nœud spécifique dans la matrice.
     */
    public function showNode(string $nodeId): JsonResponse
    {
        $node = \Modules\Genealogy\Models\Position::with(['user', 'parent.user', 'children.user'])
            ->where('user_id', $nodeId)
            ->firstOrFail();

        return response()->json($node);
    }
}
