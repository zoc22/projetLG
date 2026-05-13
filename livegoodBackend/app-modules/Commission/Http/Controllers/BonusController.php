<?php

namespace Modules\Commission\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Commission\Models\Commission;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class BonusController extends Controller
{
    /**
     * Rapport détaillé des bonus par type.
     */
    public function reportByType(): JsonResponse
    {
        $report = Commission::select('source', DB::raw('SUM(amount) as total'))
            ->where('user_id', auth()->id())
            ->groupBy('source')
            ->get();

        return response()->json($report);
    }
}
