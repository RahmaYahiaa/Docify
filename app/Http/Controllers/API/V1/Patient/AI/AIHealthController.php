<?php

namespace App\Http\Controllers\API\V1\Patient\AI; // التعديل هنا

use App\Actions\Patient\AI\BuildPatientHealthDataAction;
use App\Http\Controllers\Controller;
use App\Services\AI\MachineLearningService;
use App\Services\AI\MLPredictionService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AIHealthController extends Controller
{

    public function analyze( BuildPatientHealthDataAction $dataBuilder, MLPredictionService $mlService ): JsonResponse {
        try {
            Log::info('ai-health debug - RAW request inspection', [
                'all_headers' => request()->headers->all(),
                'server_HTTP_ACCEPT_LANGUAGE' => $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'NOT_SET',
                'getHeader_Accept-Language' => request()->header('Accept-Language'),
                'getHeader_lowercase' => request()->header('accept-language'),
            ]);

            $healthData = $dataBuilder->execute(auth()->id(), app()->getLocale());

            Log::info('ai-health debug - outgoing request', [
                'accept_language_header' => request()->header('Accept-Language'),
                'resolved_app_locale' => app()->getLocale(),
                'language_sent_to_ai_service' => $healthData['language'] ?? null,
            ]);

            $analysisResult = $mlService->predict($healthData);

            Log::info('ai-health debug - incoming response', [
                'language_returned_by_ai_service' => $analysisResult['language'] ?? null,
                'ai_provider' => $analysisResult['ai_provider'] ?? null,
                'fallback_used' => $analysisResult['fallback_used'] ?? null,
            ]);

             return $this->ok($analysisResult);
            } catch (Exception $e) {
            return $this->serverError( $e->getMessage());
        }
    }
}