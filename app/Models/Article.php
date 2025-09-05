<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\ArticleStatus;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'excerpt',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => ArticleStatus::class,
            'published_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', ArticleStatus::PUBLISHED);
    }

    public function scopePendingReview($query)
    {
        return $query->where('status', ArticleStatus::PENDING_REVIEW);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', ArticleStatus::DRAFT);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', ArticleStatus::REJECTED);
    }

    public function scopeByWriter($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function canBeSubmitted(): bool
    {
        return $this->status->canBeSubmitted();
    }

    public function canBeApproved(): bool
    {
        return $this->status->canBeApproved();
    }

    public function canBeEdited(): bool
    {
        return $this->status->canBeEdited();
    }
}
