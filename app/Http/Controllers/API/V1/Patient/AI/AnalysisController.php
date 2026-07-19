<?php

namespace App\Http\Controllers\API\V1\Patient\AI;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Patient\Reports\AnalyzeRequest;
use App\Services\AI\LabAnalysisService;
use Illuminate\Http\JsonResponse;

class AnalysisController extends Controller
{
    public function __invoke( AnalyzeRequest $request,   LabAnalysisService $labAnalysisService   ): JsonResponse {

        $language = $request->user()->language ?? 'ar';

        $result = $labAnalysisService->analyze($request->file('file'), $language );
     return $this->ok($result);
    }
}
