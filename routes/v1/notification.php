<?php

use App\Enums\Notification\DeliveryChannel;
use App\Enums\Notification\NotificationType;
use App\Http\Controllers\API\V1\Notification\FcmTokenController;
use App\Http\Controllers\API\V1\Notification\NotificationController;
use App\Models\MedicationDose;
use App\Models\User\User;
use App\Services\Notification\DeliveryService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('device-token', [FcmTokenController::class, 'storeToken']);
    // Route::post('notification', [FcmTokenController::class, 'storeToken']);

    Route::get('notifications', [NotificationController::class, 'index']);
    Route::delete('notifications/{notification}', [NotificationController::class, 'destory']);
    Route::delete('notifications', [NotificationController::class, 'deleteAllNotifications']);
    Route::patch('notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
    Route::patch('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);

    // Route::post('/test-fcm', function (Request $request) {
    //     $user = User::find($request->user_id);
    //     $service = app(App\Services\Notification\DeliveryService::class);

    //     $service->notifyUser(
    //         $user,
    //         NotificationType::MEDICATIONS,
    //         "test",
    //         "testt"
    //     );
    //     return response()->json(['message' => 'Notification Sent!']);
    // });

    Route::get('/debug/medication-doses', function () {
        $doses = MedicationDose::where('taken', false)
            ->whereNull('notified_at')
            ->where('dose_time', '<=', now())
            ->pluck('id');

        return response()->json([
            'count' => $doses->count(),
            'ids' => $doses,
            'now' => now(),
        ]);
    });

    Route::get('/debug/events', function () {
        Artisan::call('event:list');

        return nl2br(Artisan::output());
    });

    Route::get('/debug/dose/{id}', function ($id) {

        $dose = MedicationDose::with([
            'user',
            'medication',
            'prescriptionItem.prescription.patient'
        ])->find($id);

        return response()->json([
            'dose_id' => $dose?->id,
            'user_id' => $dose?->user?->id,
            'user_exists' => $dose?->user ? true : false,
            'medication_id' => $dose?->medication?->id,
            'medication_name' => $dose?->medication?->name,
            'patient_id' => $dose?->prescriptionItem?->prescription?->patient?->id,
        ]);
    });

    Route::get('/debug/test-notification', function () {

        $user = User::find(6);

        app(DeliveryService::class)->notifyUser(
            $user,
            NotificationType::MEDICATIONS,
            'Medication Reminder',
            'Test Reminder',
            [],
            [
                DeliveryChannel::PUSH,
                DeliveryChannel::IN_APP,
            ]
        );

        return response()->json([
            'success' => true
        ]);
    });

    Route::get('/debug/dose-status/{id}', function ($id) {

        $dose = MedicationDose::find($id);

        return response()->json([
            'id' => $dose->id,
            'notified_at' => $dose->notified_at,
            'taken' => $dose->taken,
        ]);
    });

    Route::get('/debug/run-command', function () {

        Artisan::call('medications:notify');

        return response()->json([
            'output' => Artisan::output()
        ]);
    });
});
