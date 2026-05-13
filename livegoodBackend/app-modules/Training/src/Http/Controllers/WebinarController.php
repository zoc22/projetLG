<?php

namespace Modules\Training\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Training\Models\Webinar;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class WebinarController extends Controller
{
    /**
     * Liste des prochains webinaires.
     */
    public function index(): JsonResponse
    {
        $webinars = Webinar::where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at', 'asc')
            ->with('trainer')
            ->get();
            
        return response()->json($webinars);
    }

    /**
     * Archives des anciens Replays.
     */
    public function replays(): JsonResponse
    {
        $replays = Webinar::whereNotNull('replay_url')
            ->where('scheduled_at', '<', now())
            ->orderBy('scheduled_at', 'desc')
            ->get();
            
        return response()->json($replays);
    }
}
