<?php

namespace Modules\Commission\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Modules\Commission\Enums\CommissionStatusEnum;
use Modules\Commission\Enums\CommissionTypeEnum;

class Commission extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'type',
        'source',
        'period_string'
    ];

    protected $casts = [
        'status' => CommissionStatusEnum::class,
        'type'   => CommissionTypeEnum::class,
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    // Relations avec les types spécifiques de bonus
    public function fastStart() { return $this->hasOne(FastStartBonus::class); }
    public function matrix()    { return $this->hasOne(MatrixBonus::class); }
    public function matching()  { return $this->hasOne(MatchingBonus::class); }
    public function retail()    { return $this->hasOne(RetailBonus::class); }
    public function influencer(){ return $this->hasOne(InfluencerBonus::class); }
    public function diamond()   { return $this->hasOne(DiamondPool::class); }
}
