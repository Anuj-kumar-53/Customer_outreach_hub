<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    public const STATUS_OPEN = 'open';

    public const STATUS_REVIEWING = 'reviewing';

    public const STATUS_RESOLVED = 'resolved';

    public const STATUS_DISMISSED = 'dismissed';

    public const CATEGORY_SPAM = 'spam';

    public const CATEGORY_ABUSE = 'abuse';

    public const CATEGORY_FAKE_BUSINESS = 'fake_business';

    public const CATEGORY_INAPPROPRIATE = 'inappropriate_content';

    public const CATEGORY_OTHER = 'other';

    protected $fillable = [
        'reporter_id',
        'reported_user_id',
        'reported_campaign_id',
        'reported_comment_id',
        'category',
        'description',
        'status',
        'resolved_by',
        'resolution_notes',
    ];

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function reportedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }

    public function reportedCampaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'reported_campaign_id');
    }

    public function reportedComment(): BelongsTo
    {
        return $this->belongsTo(CampaignComment::class, 'reported_comment_id');
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
