<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\ArticleStatus;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => ArticleStatus::class,
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', ArticleStatus::PUBLISHED);
    }

    public function scopePendingReview($query)
    {
        return $query->where('status', ArticleStatus::PENDING_REVIEW);
    }

    public function scopeByWriter($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Helper methods
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
