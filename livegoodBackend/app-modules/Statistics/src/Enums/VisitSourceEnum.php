<?php

namespace Modules\Statistics\Enums;

/**
 * Sources d'origine du trafic sur les sites de capture / retail.
 */
enum VisitSourceEnum: string
{
    case DIRECT   = 'direct';   // Saisie directe de l'URL
    case FACEBOOK = 'facebook';
    case GOOGLE   = 'google';
    case YOUTUBE  = 'youtube';
    case EMAIL    = 'email';
    case TIKTOK   = 'tiktok';
    case OTHER    = 'other';
}
