<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Exception;

class MLPredictionService
{
    public function predict(array $data)
    {
        $url = "https://test.maknoun.sa/analyze-vitals";

        try {
            $response = Http::withoutVerifying()
                ->timeout(200)
                ->post($url, $data);

                if ($response->failed()) {

    $errorData = $response->json();

    $errorMessage = $errorData['detail']
                    ?? $errorData['error']
                    ?? $errorData['message'] ;


    throw new \Exception($errorMessage, $response->status());
}
// if ($response->failed()) {

//     dd([
//         'full_response' => $response->json(),
//         'status' => $response->status(),
//         'sent_data' => $data
//     ]);
// }

            return $response->json();

        } catch (Exception $e) {

            throw $e;
        }
    }
}