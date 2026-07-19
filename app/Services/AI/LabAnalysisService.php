<?php

namespace App\Services\AI;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

class LabAnalysisService
{
    public function analyze(UploadedFile $file, string $language): array
    {
        $response = Http::withoutVerifying()
            ->timeout(200)
            ->connectTimeout(60)
            ->attach(
                'file',
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            )
            ->post(    config('services.lab_ai.url') . '/analyze',
                //'http://medical-lab-analyzer.maknoun.sa/analyze',
                 [
                'language' => $language,
            ]);

  if ($response->serverError()) {
    logger()->error('AI server error', [
        'status' => $response->status(),
        'body' => $response->body(),
    ]);

    return [
        'error' => 'AI service is temporarily unavailable',
    ];
}   return $response->json();
    }
}