<?php

namespace App\Http\Controllers\API\V1\Doctor;

use App\Actions\Review\StoreReviewAction;
use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Doctor\Profile\StoreReviewRequest;
use App\Http\Resources\API\V1\Review\ReviewDetailsCollection;
use App\Http\Resources\API\V1\Review\ReviewDetailsResource;
use App\Models\DoctorProfile;
use App\Models\Review;
use App\Models\User\User;

class DoctorReviewController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isDoctor()) {
            throw new NotFoundException('not found');
        }
        return $this->getDoctorReviewsResponse(auth()->id());
    }

    public function doctorReviews($doctorId)
    {
        if (!auth()->user()->isPatient()) {
            throw new NotFoundException('not found');
        }
        return $this->getDoctorReviewsResponse($doctorId);
    }

    private function getDoctorReviewsResponse($userId)
    {
        $doctor = DoctorProfile::where('user_id', $userId)
            ->withReviewsStats()
            ->firstOrFail();

        $reviews = $doctor->reviews()
            ->with('user')
            ->orderBy('rating', 'desc')
            ->paginate(5);

        return response()->json([
            'stats' => [
                'reviews_count' => $doctor->reviews_count,
                'average_rating' => $doctor->average_rating,
            ],
            'reviews' => new ReviewDetailsCollection($reviews),
        ]);
    }

    public function store(StoreReviewRequest $request, StoreReviewAction $action)
    {
        $review = $action->execute($request->validated(), $request->user());

        return $this->ok(__('messages.review_created_successfully'), new ReviewDetailsResource($review));
    }
}
