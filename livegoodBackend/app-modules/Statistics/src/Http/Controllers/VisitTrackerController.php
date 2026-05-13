<?php

namespace Modules\Statistics\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Statistics\Services\VisitTrackerService;
use Illuminate\Http\Request;

/**
 * Contrôleur public pour le tracking (Capture pages).
 */
class VisitTrackerController extends Controller
{
    public function __construct(protected VisitTrackerService $tracker) {}

    /**
     * Endpoint appelé silencieusement par les landing pages pour traquer un clic.
     */
    public function track(Request $request): void
    {
        $validated = $request->validate([
            'affiliate_id' => 'required|uuid',
            'site_type'    => 'required|string',
            'source'       => 'nullable|string'
        ]);

        $this->tracker->trackVisit(
            $validated['affiliate_id'],
            $validated['site_type'],
            ['source' => $request->query('source')]
        );
    }
}
