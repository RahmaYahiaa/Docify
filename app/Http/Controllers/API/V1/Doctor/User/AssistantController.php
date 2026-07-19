<?php

namespace App\Http\Controllers\API\V1\Doctor\User;

use App\Actions\Doctor\Assistant\StoreAssistantAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Doctor\Assistant\StoreAssistantRequest;
use Illuminate\Http\Request;

class AssistantController extends Controller
{
    public function store(StoreAssistantRequest $request, StoreAssistantAction $action)
    {
        $assistant = $action->execute( $request->validated(), auth()->id()  );

       return $this->ok( __('messages.assistant_added_successfully') );
    }
}