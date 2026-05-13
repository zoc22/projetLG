<?php

namespace Modules\Marketing\Enums;

/**
 * Types de sites fournis par la plateforme à l'affilié.
 */
enum SiteTypeEnum: string
{
    case CORPORATE   = 'corporate';   // Site institutionnel
    case RETAIL      = 'retail';      // Boutique en ligne
    case CAPTURE     = 'capture';     // Page de capture LiveGood Tour (Powerline)
}
