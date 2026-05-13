<?php

namespace Modules\Rank\Services;

use Modules\Rank\Enums\RankEnum;
use Illuminate\Support\Facades\Config;

/**
 * Service chargé de vérifier si un utilisateur remplit les conditions d'un rang.
 */
class RequirementCheckerService
{
    /**
     * Vérifie l'éligibilité pour un rang spécifique.
     */
    public function isEligible(string $userId, RankEnum $rank, array $stats): bool
    {
        $config = Config::get("rank.qualifications." . strtolower($rank->name));
        
        if (!$config) return false;

        return match($rank) {
            RankEnum::BRONZE => $this->checkBronze($config, $stats),
            RankEnum::SILVER => $this->checkSilver($config, $stats),
            RankEnum::GOLD   => $this->checkGold($config, $stats),
            RankEnum::PLATINUM => $this->checkPlatinum($config, $stats),
            RankEnum::DIAMOND  => $this->checkDiamond($config, $stats),
            default => true
        };
    }

    private function checkBronze(array $conf, array $stats): bool
    {
        return $stats['direct_active'] >= $conf['direct_active'];
    }

    private function checkSilver(array $conf, array $stats): bool
    {
        if ($stats['total_active'] < $conf['total_active']) return false;

        return ($stats['direct_active'] >= $conf['option_1']['direct_active']) 
            || ($stats['legs_with_bronze'] >= $conf['option_2']['bronze_legs']);
    }

    private function checkGold(array $conf, array $stats): bool
    {
        if ($stats['total_active'] < $conf['total_active']) return false;

        return ($stats['direct_active'] >= $conf['option_1']['direct_active']) 
            || ($stats['legs_with_silver'] >= $conf['option_2']['silver_legs']);
    }

    private function checkPlatinum(array $conf, array $stats): bool
    {
        if ($stats['total_active'] < $conf['total_active']) return false;

        return ($stats['direct_active'] >= $conf['option_1']['direct_active']) 
            || ($stats['legs_with_gold'] >= $conf['option_2']['gold_legs']);
    }

    private function checkDiamond(array $conf, array $stats): bool
    {
        return ($stats['total_active'] >= $conf['total_active']) 
            && ($stats['legs_with_platinum'] >= $conf['platinum_legs']);
    }
}
