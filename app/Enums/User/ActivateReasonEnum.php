<?php

namespace App\Enums\User;

enum ActivateReasonEnum: string
{
    case ISSUE_RESOLVED = 'issue_resolved';
    case USER_APPEAL = 'user_appeal';
    case ADMIN_DECISION = 'admin_decision';
    case DOCUMENTS_VERIFIED = 'user_verified';
    case PAYMENT_RESOLVED = 'payment_resolved';
    case SECURITY_CLEARED = 'security_cleared';
    case PROFILE_UPDATED = 'profile_updated';
    case MANUAL_REVIEW_APPROVED = 'manual_review_approved';
    case OTHER = 'other';


    public function label(): string
    {
        return match ($this) {
            self::ISSUE_RESOLVED => 'Issue resolved',
            self::USER_APPEAL => 'User appeal accepted',
            self::ADMIN_DECISION => 'Admin decision',
            self::DOCUMENTS_VERIFIED => 'User verified',
            self::PAYMENT_RESOLVED => 'Payment issue resolved',
            self::SECURITY_CLEARED => 'Security check cleared',
            self::PROFILE_UPDATED => 'Profile information updated',
            self::MANUAL_REVIEW_APPROVED => 'Approved after manual review',
            self::OTHER => 'Other',
        };
    }

    public static function options(): array
    {
        return array_map(fn($case) => [
            'key' => $case->value,
            'label' => $case->label(),
        ], self::cases());
    }
}
