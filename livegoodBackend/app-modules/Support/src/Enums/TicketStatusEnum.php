<?php

namespace Modules\Support\Enums;

/**
 * Statuts possibles pour un ticket de support.
 * Ref: Diagramme de classe (StatutTicket)
 */
enum TicketStatusEnum: string
{
    case OPEN        = 'open';     // Ouvert
    case IN_PROGRESS = 'progress'; // En cours
    case RESOLVED    = 'resolved'; // Résolu
    case CLOSED      = 'closed';   // Fermé
}
