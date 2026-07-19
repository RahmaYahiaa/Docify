<?php

namespace App\Http\Controllers\API\V1\Patient;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\API\V1\Authentication\Doctor\DoctorRegisterController;
use App\Http\Controllers\API\V1\Authentication\ForgetPassword\ForgetPasswordController;
use App\Http\Controllers\API\V1\Authentication\GoogleLoginController;
use App\Http\Controllers\API\V1\Authentication\LoginController;
use App\Http\Controllers\API\V1\Authentication\Patient\RegisterController;
use App\Http\Controllers\API\V1\Doctor\DoctorReviewController;
use App\Http\Controllers\API\V1\Patient\AI\AIHealthController;
use App\Http\Controllers\API\V1\Patient\AI\AnalysisController;
use App\Http\Controllers\API\V1\Patient\Appointment\AppointmentController;
use App\Http\Controllers\API\V1\Patient\Appointment\DoctorsController;
use App\Http\Controllers\API\V1\Patient\Appointment\PatientAvailabilityController;
use App\Http\Controllers\API\V1\Patient\Measurements;
use App\Http\Controllers\API\V1\Patient\Measurements\MeasurementPredictionController;
use App\Http\Controllers\API\V1\Patient\Measurements\UserMeasurementController;
use App\Http\Controllers\API\V1\Patient\Prescription\PrescriptionController;
use App\Http\Controllers\API\V1\Patient\Profile\HealthCardController;
use App\Http\Controllers\API\V1\Patient\Profile\LabController;
use App\Http\Controllers\API\V1\Patient\Profile\MedicalDataController;
use App\Http\Controllers\API\V1\Patient\Profile\MedicationController;
use App\Http\Controllers\API\V1\Patient\Profile\PatientProfileController;
use App\Http\Controllers\API\V1\Payment\PaymentStatusController;
use App\Http\Controllers\API\V1\SpecializationController;
use Database\Seeders\AllergySeeder;
use Database\Seeders\ChronicConditionSeeder;
use Database\Seeders\MeasurementTypeSeeder;
use Database\Seeders\MedicationSeeder;
use Database\Seeders\UserMeasurementSeeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

// rahma
// Route::post('login', [LoginController::class, 'login'])->withoutMiddleware('auth:sanctum');
Route::post('register/patient', [RegisterController::class, 'register'])->withoutMiddleware('auth:sanctum');
Route::post('register/doctor', [DoctorRegisterController::class, 'register'])->withoutMiddleware('auth:sanctum');

// Route::post('/doctor/{user}/upload-certificate', [GoogleLoginController::class, 'upload'])->withoutMiddleware('auth:sanctum');

// Route::post('/forget-password', [ForgetPasswordController::class, 'sendOtp'])->withoutMiddleware('auth:sanctum');
// Route::post('/reset-password', [ForgetPasswordController::class, 'resetPassword'])->withoutMiddleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {

    // View doctor's available slots
    Route::get('/doctors/{doctor}/available-slots', [PatientAvailabilityController::class, 'index'])
        ->name('patient.doctor.slots.index');

    // Appointments management
    Route::prefix('appointments')->group(function () {

        //video appointment booking
        Route::post('/initiate', [AppointmentController::class, 'initiate'])
            ->name('patient.appointments.initiate');
        // In-person appointment booking
        Route::post('/', [AppointmentController::class, 'store'])
            ->name('patient.appointments.store');
        Route::get('/', [AppointmentController::class, 'index'])
            ->name('patient.appointments.index');

        Route::get('/{appointment}', [AppointmentController::class, 'show'])
            ->name('patient.appointments.show');

        Route::patch('/{appointment}/cancel', [AppointmentController::class, 'cancel'])
            ->name('patient.appointments.cancel');

        Route::patch('/{appointment}/reschedule', [AppointmentController::class, 'reschedule'])
            ->name('patient.appointments.reschedule');

        Route::get('/{appointment}/video-session', [AppointmentController::class, 'joinVideoSession'])
            ->name('patient.appointments.video-session');
    });

    //payment status
    Route::get('/payment-status', [PaymentStatusController::class, '__invoke'])
        ->name('patient.payment.status');
    Route::get('/doctors', [DoctorsController::class, 'index'])
        ->name('patient.doctors.index');
    Route::get('/doctors/{doctor}', [DoctorsController::class, 'show'])
        ->name('patient.doctors.show');
    Route::get('/specializations', [SpecializationController::class, 'index'])
        ->name('patient.specializations.index');
});


// Patient_prescription
Route::name('patient')->group(function () {
    Route::apiResource('prescriptions', PrescriptionController::class);
});
//sendRequest
//  Route::post('send-request', [PrescriptionController::class, 'sendRequest']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('prescriptions/{prescription}/renew', [PrescriptionController::class, 'renew']);
    Route::get('prescriptions', [PrescriptionController::class, 'index']);
    Route::get('prescriptions/{prescription}', [PrescriptionController::class, 'show']);
});

//patient_profile
Route::middleware('auth:sanctum')->group(function () {
    //dashboard
    Route::get('dashboard', [PatientDashboardController::class, 'dashboard']);

    //basic info
    Route::get('profile', [PatientProfileController::class, 'show']);
    Route::put('profile', [PatientProfileController::class, 'update']);
    Route::post('profile/photo', [PatientProfileController::class, 'uploadPhoto']);

    //medical data
    Route::get('medical-data', [MedicalDataController::class, 'show']);
    Route::put('medical-data', [MedicalDataController::class, 'update']);
    Route::apiResource('medications', MedicationController::class);

    //display health card
    Route::get('/healthcard', [HealthCardController::class, 'show']);
});
//lab-analysis
Route::post('/analyze', AnalysisController::class);
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/lab-reports', [LabController::class, 'store']);
    Route::get('/lab-reports', [LabController::class, 'index']);
});


Route::get('available-medications', [MedicationController::class, 'getAllMedications']);
Route::get('chronic-conditions', [MedicalDataController::class, 'chronicConditionsList']);
Route::get('allergies', [MedicalDataController::class, 'allergiesList']);
Route::get('/healthcard/view/{uuid}', [HealthCardController::class, 'view']);

//measurements
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/measurements', [UserMeasurementController::class, 'store']);
    Route::post('/measurements/predict', [MeasurementPredictionController::class, 'predict']);
    Route::get('measurements/last', [MeasurementPredictionController::class, 'getSummary']);
    Route::get('/ai-health', [AIHealthController::class, 'analyze']);
});

//review
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/doctors/{doctor}/reviews', [DoctorReviewController::class, 'doctorReviews']);
    Route::post('/reviews', [DoctorReviewController::class, 'store']);
});



Route::get('/AllergySeeder', function () {
    Artisan::call('db:seed', [
        '--class' => AllergySeeder::class,
        '--force' => true,
    ]);

    return response()->json([
        'message' => 'AllergySeeder executed successfully',
        'output' => Artisan::output(),
    ]);
});


Route::get('/ChronicConditionSeeder', function () {
    Artisan::call('db:seed', [
        '--class' => ChronicConditionSeeder::class,
        '--force' => true,
    ]);

    return response()->json([
        'message' => 'AllergySeeder executed successfully',
        'output' => Artisan::output(),
    ]);
});

Route::get('/UserMeasurementSeeder', function () {
    Artisan::call('db:seed', [
        '--class' => UserMeasurementSeeder::class,
        '--force' => true,
    ]);

    return response()->json([
        'message' => 'MedicationSeeder executed successfully',
        'output' => Artisan::output(),
    ]);
});

Route::get('/check-notifications', function () {
    try {
        $checks = [];

        Artisan::call('schedule:run');
        $checks['schedule_run'] = Artisan::output();

        $checks['failed_jobs'] = DB::table('failed_jobs')->count();
        $checks['pending_jobs'] = DB::table('jobs')->count();

        $checks['firebase_exists'] =
            file_exists(storage_path('app/firebase_credentials.json'));

        return response()->json([
            'status' => 'success',
            'checks' => $checks,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
        ]);
    }
});
//MedicationSeeder
Route::get('/fresh-seed', function () {

    if (request('key') !== config('app.key')) {
        abort(403, 'Unauthorized');
    }

    try {
        Artisan::call('migrate:fresh', [
            '--seed' => true,
            '--force' => true,
        ]);

        return response()->json([
            'message' => 'Fresh migrate + seed done',
            'output' => Artisan::output(),
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed',
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
        ], 500);
    }
});

Route::get('/migrate', function () {

    Artisan::call('migrate', [
        '--force' => true,
    ]);

    return response()->json([
        'message' => 'Migrate done',
        'output' => Artisan::output()
    ]);
});
Route::get('/check-deploy', function () {
    return response()->json([
        'controller_content' => file_get_contents(app_path('Http/Controllers/API/V1/Patient/Profile/LabController.php')),
        'routes_list' => array_map(function ($route) {
            return [
                'uri' => $route->uri(),
                'methods' => $route->methods(),
                'action' => $route->getActionName(),
            ];
        }, app('router')->getRoutes()->getRoutesByMethod()['POST'] ?? []),
    ]);
});
Route::get('/clear-cache-debug', function () {
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');

    $opcacheStatus = function_exists('opcache_reset') ? opcache_reset() : 'opcache not available';

    return response()->json([
        'message' => 'Cache cleared',
        'opcache_reset' => $opcacheStatus,
    ]);
});
// This route is for setting up storage symlink and permissions, which is required for media uploads to work properly. It should be protected and used only once during deployment or when setting up a new environment.
Route::get('/setup-storage', function () {

    // Basic protection
    if (request('key') !== config('app.key')) {
        abort(403, 'Unauthorized');
    }

    $results = [];

    // 1. Storage Link
    try {
        $linkPath = public_path('storage');
        $targetPath = storage_path('app/public');

        if (is_link($linkPath)) {
            unlink($linkPath);
            $results['storage_link'] = 'Old link removed, creating new one';
        }

        Artisan::call('storage:link', ['--force' => true]);
        $results['storage_link'] = 'Done  ' . Artisan::output();
    } catch (\Exception $e) {
        $results['storage_link'] = 'Failed  ' . $e->getMessage();
    }

    // 2. Storage Permissions
    try {
        $storagePath = storage_path('app/public');

        if (function_exists('chmod')) {
            chmod($storagePath, 0775);
            $results['permissions'] = '';
        } else {
            $results['permissions'] = 'Skipped — chmod not available';
        }
    } catch (\Exception $e) {
        $results['permissions'] = 'Failed  ' . $e->getMessage();
    }

    // 3. Verify Link Works
    $results['link_exists'] = is_link(public_path('storage')) ? 'Yes ' : 'No ';
    $results['target_exists'] = is_dir(storage_path('app/public')) ? 'Yes ' : 'No ';
    $results['app_url'] = config('app.url');
    $results['media_disk'] = config('media-library.disk_name');
    $results['filesystem_default'] = config('filesystems.default');

    return response()->json([
        'message' => 'Storage setup completed',
        'results' => $results,
    ]);
});

Route::get('/dev/fix', function () {
    if (!app()->environment('local', 'staging')) {
        abort(403, 'Not allowed in production');
    }
    Artisan::call('optimize:clear');
    Artisan::call('dump-autoload');
    Artisan::call('migrate', [
        '--force' => true,
    ]);
    return response()->json([
        'message' => 'Dev fix executed successfully',
        'output' => Artisan::output(),
    ]);
});

Route::get('/queue-work', function () {
    if (request('key') !== config('app.key')) {
        abort(403, 'Unauthorized');
    }

    try {
        Artisan::call('queue:work', [
            '--stop-when-empty' => true,
            '--tries' => 3
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Queue processed successfully',
            'output'  => Artisan::output() ?: 'No jobs were in the queue.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status'  => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});

Route::get('/test-schedule-list', function () {
    try {
        Artisan::call('schedule:list');
        $output = Artisan::output();

        return response()->json([
            'status' => 'success',
            'message' => 'Laravel Schedule List fetched successfully',
            'data' => explode("\n", trim($output))
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});

Route::get('/check-files', function () {
    return response()->json([
        'jobs' => scandir(app_path('Jobs/Chat')),
        'services_chat' => scandir(app_path('Services/Chat')),
    ]);
});
