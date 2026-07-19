<?php

use App\Models\User\User;
use Illuminate\Support\Facades\Auth;

/**
 * Get the supported languages.
 *
 * @return list<string> // Or use `string[]` if order doesn't matter
 */
if (! function_exists('languages')) {
    function languages(): array
    {
        return ['ar', 'en'];
    }
}

if (! function_exists('authUser')) {
    function authUser(): ?User
    {
        if (auth('sanctum')->check()) {
            return auth('sanctum')->user();
        }

        return null;
    }
}

if (! function_exists('authUserId')) {
    function authUserId(): ?int
    {
        return Auth::guard('sanctum')->id();
    }
}

if (! function_exists('authCheck')) {
    function authCheck(): bool
    {
        return Auth::guard('sanctum')->check();
    }
}

/**
 * Generate unique barcode numbers.
 *
 * @template TModel of \Illuminate\Database\Eloquent\Model
 *
 * @param  class-string<TModel>  $model  The model class name.
 * @param  string  $column  The column name to check uniqueness against.
 * @param  string|null  $prefix  Optional prefix for the barcode.
 * @param  int  $count  Number of barcode numbers to generate.
 * @return ($count is 1 ? string : array<string>) A single barcode string or an array of barcode strings.
 */
function generateBarcodeNumber(string $model, string $column = 'code', ?string $prefix = '', int $count = 1): array|string
{
    /** @var array<string> $numbers */
    $numbers = [];

    $maxDigits = 10 - strlen($prefix);
    $maxValue = (int) str_repeat('9', $maxDigits);

    for ($i = 0; $i < $count; $i++) {
        $randomNumber = mt_rand(0, $maxValue);
        $number = $prefix.str_pad((string) $randomNumber, $maxDigits, '0', STR_PAD_LEFT);
        $numbers[$i] = $number;
    }

    /** @var array<string> $existingCodes */
    $existingCodes = $model::whereIn($column, $numbers)->pluck($column)->all();

    foreach ($numbers as $index => $number) {
        while (in_array($number, $existingCodes, true)) {
            $randomNumber = mt_rand(0, $maxValue);
            $number = $prefix.str_pad((string) $randomNumber, $maxDigits, '0', STR_PAD_LEFT);
            if (! in_array($number, $numbers, true) && ! in_array($number, $existingCodes, true)) {
                $numbers[$index] = $number;
                break;
            }
        }
    }

    $result = array_values($numbers);

    return $count === 1 ? $result[0] : $result;
}
