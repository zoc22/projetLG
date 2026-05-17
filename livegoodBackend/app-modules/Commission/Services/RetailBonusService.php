<?php

namespace Modules\Commission\Services;

use Modules\Commission\Models\Commission;
use Modules\Commission\Models\RetailBonus;
use Modules\Commission\DTO\BonusCalculationDTO;
use Modules\Commission\Enums\BonusTypeEnum;
use Modules\Commission\Enums\CommissionTypeEnum;
use Modules\Shop\Models\Order;
use Modules\Shop\Models\OrderItem;

/**
 * Bonus de vente au détail (50% de la différence prix public/membre).
 */
class RetailBonusService
{
    public function __construct(protected CommissionService $commissionService) {}

    /**
     * Calcule le bonus de vente au détail pour une commande publique.
     */
    public function processRetailOrder(Order $order): void
    {
        // Seules les commandes passées par des non-affiliés (prix public) génèrent ce bonus
        if (!$order->is_retail_order || !$order->referral_id) return;

        $totalBonus = 0;
        foreach ($order->items as $item) {
            $product = $item->product;
            $diff = $product->public_price - $product->member_price;
            $totalBonus += ($diff * 0.5) * $item->quantity;
        }

        if ($totalBonus > 0) {
            $dto = new BonusCalculationDTO(
                userId: $order->referral_id,
                amount: $totalBonus,
                bonusType: BonusTypeEnum::RETAIL,
                periodString: now()->format('Y-m'),
                sourceId: $order->id
            );

            $commission = $this->commissionService->storeCommission($dto, CommissionTypeEnum::MONTHLY);

            RetailBonus::create([
                'commission_id' => $commission->id,
                'order_id'      => $order->id,
                'amount'        => $totalBonus
            ]);
        }
    }
}

