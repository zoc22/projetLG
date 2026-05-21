<?php

namespace Modules\Genealogy\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Genealogy\Services\GenealogyService;
use Modules\Genealogy\Services\MatrixService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * API pour gérer la généalogie des membres.
 */
class GenealogyController extends Controller
{
    public function __construct(
        protected GenealogyService $genealogyService
    ) {}

    /**
     * Obtenir les informations de généalogie de l'utilisateur connecté.
     */
    public function index(): JsonResponse
    {
        $userId = Auth::id();
        $node   = \Modules\Genealogy\Models\GenealogyNode::where('user_id', $userId)
            ->with(['referrals.user', 'sponsor.user'])
            ->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data'   => $node
        ]);
    }

    /**
     * Obtenir l'arbre unilevel pour la vue TreeView.
     */
    public function tree(): JsonResponse
    {
        $userId = Auth::id();
        $node = \Modules\Genealogy\Models\GenealogyNode::where('user_id', $userId)
            ->with('user')
            ->firstOrFail();

        $children = \Modules\Genealogy\Models\GenealogyNode::where('sponsor_id', $userId)
            ->with('user')
            ->get()
            ->map(function ($child) {
                return [
                    'name' => $child->user->prenom . ' ' . $child->user->nom,
                    'rank' => $child->rank,
                    'count' => \Modules\Genealogy\Models\GenealogyNode::where('sponsor_id', $child->user_id)->count()
                ];
            });

        return response()->json([
            'root' => [
                'name' => $node->user->prenom . ' ' . $node->user->nom,
                'rank' => $node->rank
            ],
            'children' => $children
        ]);
    }

    /**
     * Obtenir les détails d'un nœud spécifique.
     */
    public function showNode(string $nodeId): JsonResponse
    {
        $node = \Modules\Genealogy\Models\GenealogyNode::with(['user', 'sponsor.user'])
            ->where('user_id', $nodeId)
            ->firstOrFail();

        return response()->json($node);
    }

    /**
     * Rechercher un membre dans la généalogie.
     */
    public function search(): JsonResponse
    {
        $query = request('q');
        $users = \Modules\Authentication\Models\User::where('nom', 'ilike', "%$query%")
            ->orWhere('prenom', 'ilike', "%$query%")
            ->orWhere('email', 'ilike', "%$query%")
            ->limit(10)
            ->get();

        return response()->json($users);
    }

    /**
     * Obtenir la downline (filleuls) d'un utilisateur.
     */
    public function downline(string $userId): JsonResponse
    {
        $level = request('level');
        $query = \Modules\Genealogy\Models\GenealogyNode::with('user')
            ->where('sponsor_id', $userId);

        if ($level) {
            // Logique de niveau relatif à implémenter si nécessaire
        }

        return response()->json($query->get());
    }

    /**
     * Obtenir l'upline (parrains) d'un utilisateur.
     */
    public function upline(string $userId): JsonResponse
    {
        $upline = [];
        $currentNode = \Modules\Genealogy\Models\GenealogyNode::where('user_id', $userId)->first();

        while ($currentNode && $currentNode->sponsor_id) {
            $sponsor = \Modules\Genealogy\Models\GenealogyNode::with('user')
                ->where('user_id', $currentNode->sponsor_id)
                ->first();
            
            if ($sponsor) {
                $upline[] = $sponsor;
                $currentNode = $sponsor;
            } else {
                break;
            }
        }

        return response()->json($upline);
    }

    /**
     * Mettre à jour manuellement le rang (utile pour débug ou admin).
     */
    public function refreshRank(): JsonResponse
    {
        $rank = $this->genealogyService->refreshMemberRank(Auth::id());

        return response()->json([
            'status' => 'success',
            'rank'   => $rank
        ]);
    }
}
