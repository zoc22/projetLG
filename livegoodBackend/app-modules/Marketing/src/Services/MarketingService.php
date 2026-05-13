<?php

namespace Modules\Marketing\Services;

use Modules\Marketing\Models\AffiliateSite;
use Modules\Marketing\Models\Lead;
use Modules\Marketing\Enums\LeadStatusEnum;
use Illuminate\Support\Facades\DB;

/**
 * Service pour la gestion des sites et de la capture de leads.
 */
class MarketingService
{
    /**
     * Enregistre un nouveau lead et met à jour les compteurs du site.
     */
    public function captureLead(array $data): Lead
    {
        return DB::transaction(function() use ($data) {
            $lead = Lead::create([
                'first_name'     => $data['first_name'],
                'last_name'      => $data['last_name'],
                'email'          => $data['email'],
                'affiliate_id'   => $data['affiliate_id'],
                'site_id'        => $data['site_id'] ?? null,
                'preenrolled_at' => now(),
                'cutoff_at'      => now()->next('Thursday')->setTime(23, 59, 59),
                'status'         => LeadStatusEnum::ACTIVE
            ]);

            if ($lead->site_id) {
                AffiliateSite::where('id', $lead->site_id)->increment('leads_count');
            }

            // Trigger Event for Statistics & Notifications
            // event(new LeadCaptured($lead));

            return $lead;
        });
    }

    /**
     * Convertit un lead en membre payant.
     */
    public function convertLead(string $leadId, string $newUserId): void
    {
        $lead = Lead::findOrFail($leadId);
        
        $lead->update([
            'status'            => LeadStatusEnum::CONVERTED,
            'converted_user_id' => $newUserId
        ]);

        if ($lead->site_id) {
            AffiliateSite::where('id', $lead->site_id)->increment('sales_count');
        }
    }

    /**
     * Récupère la liste des leads actifs pour un affilié.
     */
    public function getActiveLeadsForAffiliate(string $userId)
    {
        return Lead::where('affiliate_id', $userId)
            ->where('status', LeadStatusEnum::ACTIVE)
            ->orderBy('preenrolled_at', 'desc')
            ->get();
    }
}
