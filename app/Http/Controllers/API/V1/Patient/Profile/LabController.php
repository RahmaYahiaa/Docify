<?php

namespace App\Http\Controllers\API\V1\Patient\Profile;

use App\Actions\Patient\Profile\UploadLabReportAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Patient\Profile\StoreLabReportRequest;
use App\Http\Resources\API\V1\Patient\Reports\LabReportResource;
use App\Models\User\User;
use Illuminate\Http\Request;

class LabController extends Controller
{
    public function store(StoreLabReportRequest $request, UploadLabReportAction $action)
    {
        $action->execute($request->user(), $request->file('file'));
        return $this->ok(__('messages.Lab_report_uploaded_successfully'));
    }

    public function index(Request $request)
    {
        return $this->ok(data: LabReportResource::collection($request->user()->getMedia(User::LAB_REPORTS)));
    }
}