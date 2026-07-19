<?php

namespace App\Enums\User;

enum SuspendReasonEnum: string
{
    case PENDING_VERIFICATION = 'pending_verification';
    case INCOMPLETE_PROFILE = 'incomplete_profile';
    case MISSING_DOCUMENTS = 'missing_documents';
    case UNDER_REVIEW = 'under_review';
    case TEMP_POLICY_VIOLATION = 'temp_policy_violation';
    case SUSPICIOUS_ACTIVITY = 'suspicious_activity';
    case PAYMENT_ISSUE = 'payment_issue';
    case USER_REQUEST = 'user_request';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::PENDING_VERIFICATION => 'Pending profile verification',
            self::INCOMPLETE_PROFILE => 'Incomplete profile information',
            self::MISSING_DOCUMENTS => 'Missing required documents',
            self::UNDER_REVIEW => 'Under review by admin',
            self::TEMP_POLICY_VIOLATION => 'Temporary policy violation',
            self::SUSPICIOUS_ACTIVITY => 'Suspicious activity (under investigation)',
            self::PAYMENT_ISSUE => 'Payment or subscription issue',
            self::USER_REQUEST => 'Requested by user (temporary deactivation)',
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
