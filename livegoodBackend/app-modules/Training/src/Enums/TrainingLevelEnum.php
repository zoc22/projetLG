<?php

namespace Modules\Training\Enums;

/**
 * Niveaux de difficulté ou d'éligibilité pour les formations.
 */
enum TrainingLevelEnum: string
{
    case BEGINNER     = 'beginner';      // Tout membre inscrit
    case INTERMEDIATE = 'intermediate';  // Membres Silver+
    case ADVANCED     = 'advanced';      // Membres Gold+
    case ELITE        = 'elite';         // Membres Diamond+
}
