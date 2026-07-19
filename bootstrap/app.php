<?php

use App\Exceptions\AccessDeniedHttpExceptionRenderer;
use App\Exceptions\AuthenticationExceptionRenderer;
use App\Exceptions\InsufficientDataException;
use App\Exceptions\InsufficientDataExceptionRenderer;
use App\Exceptions\InvalidFilterQueryExceptionRenderer;
use App\Exceptions\InvalidFormatExceptionRenderer;
use App\Exceptions\MethodNotAllowedHttpExceptionRenderer;
use App\Exceptions\NotFoundHttpExceptionRenderer;
use App\Exceptions\PhoneNumberFormatExceptionRenderer;
use App\Exceptions\QueryExceptionRenderer;
use App\Exceptions\UnauthorizedExceptionRenderer;
use App\Exceptions\UniqueConstraintViolationExceptionRendor;
use App\Exceptions\UnsupportedMeasureTypeException;
use App\Exceptions\UnsupportedMeasureTypeExceptionRenderer;
use App\Exceptions\ValidationExceptionRenderer;
use App\Http\Middleware\SetLanguageMiddleware;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use libphonenumber\NumberParseException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\Exceptions\InvalidFilterQuery;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Schedule; 



return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__ . '/../routes/console.php',
        // channels: __DIR__ . '/../routes/channels.php',
        health: '/up',
        using: function () {
            //  Route::middleware(['api', SetLanguageMiddleware::class])->prefix('api/v1')->group(base_path('routes/v1/api.php'));
            Route::middleware(['api', 'auth:sanctum', SetLanguageMiddleware::class])->prefix('api/admin/v1')->group(base_path('routes/v1/admin.php'));

            Route::middleware(['api', SetLanguageMiddleware::class])->prefix('api/patient/v1')->group(base_path('routes/v1/patient.php'));
            Route::middleware(['api', 'auth:sanctum', SetLanguageMiddleware::class])->prefix('api/doctor/v1')->group(base_path('routes/v1/doctor.php'));
            Route::middleware(['api', 'auth:sanctum', SetLanguageMiddleware::class])->prefix('api/assistant/v1')->group(base_path('routes/v1/assistant.php'));
            Route::middleware(['api', 'auth:sanctum', SetLanguageMiddleware::class])->prefix('api/chat/v1')->group(base_path('routes/v1/chat.php'));
            Route::middleware(['api', 'auth:sanctum', SetLanguageMiddleware::class])->prefix('api/notification/v1')->group(base_path('routes/v1/notification.php'));
            Route::middleware(['api'])->prefix('api/payment/v1')->group(base_path('routes/v1/payment.php'));


            Route::middleware(['api', SetLanguageMiddleware::class])->prefix('api/auth/v1')->group(base_path('routes/v1/auth.php'));
            Route::middleware(['web'])->group(base_path('routes/web.php'));
        },
    )
    ->withBroadcasting(
        __DIR__ . '/../routes/channels.php',
        [
            'prefix' => 'api',
            'middleware' => ['api', 'auth:sanctum'],
        ],
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withSchedule(function () {
        Schedule::command('medications:notify')->everyMinute();
        Schedule::command('appointments:reminders')->everyFiveMinutes();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        if (request()->is('api/*')) {

            //             $exceptions->renderable(function (UniqueConstraintViolationException $e) {
            //                 return (new UniqueConstraintViolationExceptionRendor)->handle($e);
            //             });

            //             // Validation Exception
            //             $exceptions->renderable(function (ValidationException $e) {
            //                 return (new ValidationExceptionRenderer)->handle($e);
            //             });

            //             // Access Denied Exception
            //             $exceptions->renderable(function (AccessDeniedHttpException $e) {
            //                 return (new AccessDeniedHttpExceptionRenderer)->handle($e->getMessage());
            //             });

            //             // Authentication Exception
            //             $exceptions->renderable(function (AuthenticationException $e) {
            //                 return (new AuthenticationExceptionRenderer)->handle(__('auth.unauthorized'));
            //             });

            //             // Not Found Exception
            //             $exceptions->renderable(function (NotFoundHttpException $e) {
            //                 return (new NotFoundHttpExceptionRenderer)->handle($e);
            //             });

            //             // Method Not Allowed Exception
            //             $exceptions->renderable(function (MethodNotAllowedHttpException $e) {
            //                 return (new MethodNotAllowedHttpExceptionRenderer)->handle($e);
            //             });

            //             // Unauthorized Exception
            //             $exceptions->renderable(function (UnauthorizedException $e) {
            //                 return (new UnauthorizedExceptionRenderer)->handle($e);
            //             });

            //             // Invalid Filter Query Exception
            //             $exceptions->renderable(function (InvalidFilterQuery $e) {
            //                 return (new InvalidFilterQueryExceptionRenderer)->handle($e);
            //             });

            //             // Phone Number Format Exception
            //             $exceptions->renderable(function (NumberParseException $e) {
            //                 return (new PhoneNumberFormatExceptionRenderer)->handle($e);
            //             });

            //             // QueryException
            //             $exceptions->renderable(function (QueryException $e) {
            //                 return (new QueryExceptionRenderer)->handle($e);
            //             });

            //             // Invalid Format Exception
            //             $exceptions->renderable(function (InvalidFormatException $e) {
            //                 return (new InvalidFormatExceptionRenderer)->handle($e);
            //             });

            //             $exceptions->renderable(function (UnsupportedMeasureTypeException $e) {
            //             return (new UnsupportedMeasureTypeExceptionRenderer)->handle($e);
            //         });


            //        $exceptions->render(function (InsufficientDataException $e, $request) {
            //     return (new InsufficientDataExceptionRenderer)->handle($e);
            // });
        }
    })->create();
