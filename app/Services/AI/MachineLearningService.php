<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;

class MachineLearningService
{
public function predict(array $data)
{
    $url = "https://vitals-prediction.maknoun.sa/predict";


    $response = Http::withoutVerifying()
        ->timeout(70)
        ->post($url, $data);
            //   dd($response->json());
    return $response->json();
}
}