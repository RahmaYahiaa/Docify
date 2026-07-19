<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasArabicSearch
{
    /**
     * Scope for searching in Arabic and English with normalization
     */
    public function scopeSearchByName(Builder $query, string $value, ?string $locale = null): void
    {
        $driver = $query->getConnection()->getDriverName();
        $normalizedValue = $this->normalizeArabicText($value);

        if ($driver === 'pgsql') {
            // PostgreSQL: Search with normalization
            $query->where(function ($q) use ($normalizedValue, $value) {
                $q->whereRaw("REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(name->>'ar', '[أإآ]', 'ا', 'g'), '[ة]', 'ه', 'g'), '[ي]', 'ى', 'g'), '[ؤ]', 'و', 'g'), '[ئ]', 'ي', 'g') ILIKE ?", ["%{$normalizedValue}%"])
                    ->orWhereRaw("name->>'en' ILIKE ?", ["%{$value}%"]);
            });
        } else {
            // MySQL: Search with normalization
            $query->where(function ($q) use ($normalizedValue, $value) {
                $q->whereRaw("REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(name->>'ar', '[أإآ]', 'ا'), '[ة]', 'ه'), '[ي]', 'ى'), '[ؤ]', 'و'), '[ئ]', 'ي') COLLATE utf8mb4_unicode_ci LIKE ? COLLATE utf8mb4_unicode_ci", ["%{$normalizedValue}%"])
                    ->orWhereRaw("name->>'en' COLLATE utf8mb4_unicode_ci LIKE ? COLLATE utf8mb4_unicode_ci", ["%{$value}%"]);
            });
        }
    }

    /**
     * Normalize Arabic text by removing diacritics and standardizing characters
     */
    private function normalizeArabicText(string $text): string
    {
        // Remove diacritics and normalize Arabic characters
        $text = str_replace(['أ', 'إ', 'آ'], 'ا', $text);
        $text = str_replace(['ة'], 'ه', $text);
        $text = str_replace(['ي'], 'ى', $text);
        $text = str_replace(['ؤ'], 'و', $text);
        $text = str_replace(['ئ'], 'ي', $text);
        $text = str_replace(['ء'], '', $text);
        $text = str_replace(['ـ'], '', $text);

        return trim($text);
    }
}
