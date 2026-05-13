<?php

namespace Modules\UserManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Authentication\Models\User;
use Modules\Authentication\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Contrôleur administratif pour la gestion des comptes utilisateurs.
 */
class UserController extends Controller
{
    /**
     * Liste paginée des utilisateurs.
     */
    public function index(): JsonResponse
    {
        $users = User::paginate(20);
        return response()->json($users);
    }

    /**
     * Voir un utilisateur spécifique.
     */
    public function show(string $id): UserResource
    {
        $user = User::findOrFail($id);
        return new UserResource($user);
    }

    /**
     * Bannir ou Suspendre un utilisateur.
     */
    public function updateStatus(Request $request, string $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->update(['status' => $request->status]);

        return response()->json(['message' => 'Status updated.']);
    }

    /**
     * Changer le parrain (Correction manuelle admin).
     */
    public function changeSponsor(Request $request, string $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->update(['sponsor_id' => $request->sponsor_id]);

        return response()->json(['message' => 'Sponsor updated.']);
    }
}
