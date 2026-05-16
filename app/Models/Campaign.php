<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'category',
        'title',
        'description',
        'image',
        'expiry_date',
        'moderation_status',
        'moderation_reason',
        'moderated_at',
        'moderated_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expiry_date' => 'datetime',
            'moderated_at' => 'datetime',
        ];
    }

    public function scopePubliclyVisible($query)
    {
        return $query->where('moderation_status', 'active');
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function comments()
    {
        return $this->hasMany(CampaignComment::class);
    }

    public function likes()
    {
        return $this->hasMany(CampaignLike::class);
    }

    public function saves()
    {
        return $this->hasMany(SavedCampaign::class);
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class);
    }
}
