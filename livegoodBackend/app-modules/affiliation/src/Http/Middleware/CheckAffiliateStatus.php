<?php

namespace Modules\Affiliation\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Modules\Affiliation\Models\Affiliate;
use Modules\Affiliation\Enums\AffiliationStatusEnum;

/**
 * Vérifie si l'utilisateur est un affilié actif avant d'autoriser l'accès.
 */
class CheckAffiliateStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $affiliate = Affiliate::where('user_id', $user->id)->first();

        if (!$affiliate || $affiliate->status !== AffiliationStatusEnum::ACTIVE) {
            return response()->json(['error' => 'Affiliate account not active.'], 403);
        }

        return $next($request);
    }
}
