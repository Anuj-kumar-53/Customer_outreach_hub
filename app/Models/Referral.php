<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'referrer_id',
        'referred_user_id',
        'campaign_id',
        'status',
        'points_earned',
    ];

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referredUser()
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'points_earned' => 'integer',
        ];
    }
}
