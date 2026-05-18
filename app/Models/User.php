<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    public const ACCOUNT_ACTIVE = 'active';

    public const ACCOUNT_SUSPENDED = 'suspended';

    public const ACCOUNT_BANNED = 'banned';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'reward_points',
        'account_status',
        'suspended_at',
        'banned_at',
        'suspension_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'reward_points' => 'integer',
            'suspended_at' => 'datetime',
            'banned_at' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function submittedReports(): HasMany
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    public function receivedReports(): HasMany
    {
        return $this->hasMany(Report::class, 'reported_user_id');
    }

    public function adminActivityLogs(): HasMany
    {
        return $this->hasMany(AdminActivityLog::class, 'admin_id');
    }

    public function business()
    {
        return $this->hasOne(Business::class);
    }

    /**
     * All campaigns owned by this user's business profile (if any).
     */
    public function campaigns()
    {
        return $this->hasManyThrough(Campaign::class, Business::class);
    }

    public function comments()
    {
        return $this->hasMany(CampaignComment::class);
    }

    public function likedCampaigns()
    {
        return $this->hasMany(CampaignLike::class);
    }

    public function savedCampaigns()
    {
        return $this->hasMany(SavedCampaign::class);
    }

    /**
     * Referrals where this user is the referrer (Phase 7).
     */
    public function referralsGiven()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    /**
     * Referrals where this user was referred (Phase 7).
     */
    public function referralsReceived()
    {
        return $this->hasMany(Referral::class, 'referred_user_id');
    }

    public function userCoupons()
    {
        return $this->hasMany(UserCoupon::class);
    }
}
