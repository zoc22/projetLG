<?php

namespace Modules\Training\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Training\Services\TrainingService;
use Modules\Training\Models\Training;
use Modules\Training\Models\Enrollment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingController extends Controller
{
    public function __construct(protected TrainingService $service) {}

    /**
     * Catalogue des formations.
     */
    public function index(): JsonResponse
    {
        $trainings = Training::with('trainer')->get();
        return response()->json($trainings);
    }

    /**
     * Commencer une formation.
     */
    public function enroll(string $id): JsonResponse
    {
        $enrollment = $this->service->enroll(Auth::id(), $id);
        return response()->json($enrollment, 201);
    }

    /**
     * Sauvegarder la progression.
     */
    public function progress(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'percentage' => 'required|integer|min:0|max:100'
        ]);

        $this->service->updateProgress(Auth::id(), $id, $validated['percentage']);

        return response()->json(['message' => 'Progress saved.']);
    }

    /**
     * Mes formations en cours.
     */
    public function myTrainings(): JsonResponse
    {
        $enrollments = Enrollment::where('user_id', Auth::id())
            ->with('training')
            ->get();
            
        return response()->json($enrollments);
    }
}
