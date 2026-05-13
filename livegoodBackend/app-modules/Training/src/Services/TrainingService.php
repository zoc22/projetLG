<?php

namespace Modules\Training\Services;

use Modules\Training\Models\Training;
use Modules\Training\Models\Enrollment;
use Illuminate\Support\Facades\DB;

/**
 * Service pour la gestion de l'apprentissage (LMS Lite).
 */
class TrainingService
{
    /**
     * Inscrit un membre à une formation.
     */
    public function enroll(string $userId, string $trainingId): Enrollment
    {
        return Enrollment::firstOrCreate([
            'user_id'     => $userId,
            'training_id' => $trainingId
        ]);
    }

    /**
     * Met à jour la progression d'un cours.
     */
    public function updateProgress(string $userId, string $trainingId, int $percentage): void
    {
        $enrollment = Enrollment::where('user_id', $userId)
            ->where('training_id', $trainingId)
            ->firstOrFail();

        $data = ['progress_percentage' => $percentage];

        if ($percentage >= 100 && !$enrollment->completed_at) {
            $data['completed_at'] = now();
            // Génération de certificat optionnelle
        }

        $enrollment->update($data);
    }

    /**
     * Liste les cours disponibles selon le rang de l'utilisateur.
     */
    public function getAvailableTrainings(string $userRank): array
    {
        // Simple filtrage par niveau
        return Training::all()->toArray(); // Logique de filtrage par rang à affiner
    }
}
