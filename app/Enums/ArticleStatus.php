<?php

namespace App\Enums;

enum ArticleStatus: string
{
    case DRAFT = 'draft';
    case PENDING_REVIEW = 'pending_review';
    case PUBLISHED = 'published';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::PENDING_REVIEW => 'Pending Review',
            self::PUBLISHED => 'Published',
            self::REJECTED => 'Rejected',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::PENDING_REVIEW => 'yellow',
            self::PUBLISHED => 'green',
            self::REJECTED => 'red',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::DRAFT => 'bg-gray-100 text-gray-800',
            self::PENDING_REVIEW => 'bg-yellow-100 text-yellow-800',
            self::PUBLISHED => 'bg-green-100 text-green-800',
            self::REJECTED => 'bg-red-100 text-red-800',
        };
    }

    public function canBeSubmitted(): bool
    {
        return $this === self::DRAFT;
    }

    public function canBeApproved(): bool
    {
        return $this === self::PENDING_REVIEW;
    }

    public function canBeRejected(): bool
    {
        return $this === self::PENDING_REVIEW;
    }

    public function canBeEdited(): bool
    {
        return in_array($this, [self::DRAFT, self::REJECTED]);
    }

    public function isVisible(): bool
    {
        return $this === self::PUBLISHED;
    }

    public function description(): string
    {
        return match ($this) {
            self::DRAFT => 'Article is in draft mode and can be edited.',
            self::PENDING_REVIEW => 'Article has been submitted and is awaiting admin review.',
            self::PUBLISHED => 'Article has been approved and is publicly visible.',
            self::REJECTED => 'Article has been rejected by admin and needs revision.',
        };
    }

    public static function getStatusesForWriter(): array
    {
        return [
            self::DRAFT,
            self::PENDING_REVIEW,
            self::PUBLISHED,
            self::REJECTED,
        ];
    }

    public static function getStatusesForAdmin(): array
    {
        return [
            self::PENDING_REVIEW,
        ];
    }

    public static function getPublicStatuses(): array
    {
        return [
            self::PUBLISHED,
        ];
    }

    public function getNextPossibleStatuses(): array
    {
        return match ($this) {
            self::DRAFT => [self::PENDING_REVIEW],
            self::PENDING_REVIEW => [self::PUBLISHED, self::REJECTED],
            self::PUBLISHED => [], // Published articles cannot change status
            self::REJECTED => [self::PENDING_REVIEW], // Can be resubmitted after revision
        };
    }

    public function canTransitionTo(ArticleStatus $newStatus): bool
    {
        return in_array($newStatus, $this->getNextPossibleStatuses());
    }
}
