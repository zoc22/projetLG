<?php

namespace Modules\Affiliation\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Affiliation\Models\Affiliate;

class AffiliateFactory extends Factory
{
    protected $model = Affiliate::class;

    public function definition(): array
    {
        return [
            'username_canonical' => $this->faker->unique()->userName,
            'referral_code'      => strtoupper($this->faker->bothify('??##?#?###')),
            'status'             => 'active',
            'rank'               => 'unranked',
            'total_commissions'  => 0,
            'pending_balance'    => 0,
        ];
    }
}
