<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ApiResponseTrait
{
    /**
     * Format the JSON response with the specified structure.
     */
    protected function formatResponse(mixed $message, mixed $data, int $code): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $code);
    }
    /**
     * Return a 200 OK JSON response.
     */
    protected function ok(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->formatResponse($message, $data, Response::HTTP_OK);
    }

    /**
     * Return a 404 Not Found JSON response.
     */
    protected function notFound(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->formatResponse($message, $data, Response::HTTP_NOT_FOUND);
    }

    /**
     * Return a 401 Unauthorized JSON response.
     */
    protected function unauthorized(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->formatResponse($message, $data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Return a 403 Forbidden JSON response.
     */
    protected function forbidden(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->formatResponse($message, $data, Response::HTTP_FORBIDDEN);
    }

    /**
     * Return a 429 Too Many Requests JSON response.
     */
    protected function tooManyRequests(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->formatResponse($message, $data, Response::HTTP_TOO_MANY_REQUESTS);
    }
    protected function error(string $message = 'Something went wrong', mixed $data = null, int $code = 500): JsonResponse
{
    return $this->formatResponse($message, $data, $code);
}

    /**
     * Return a 422 Unprocessable Entity JSON response.
     */
    protected function unprocessable(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->formatResponse($message, $data, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Return a 500 Internal Server Error JSON response.
     */
    protected function serverError(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->formatResponse($message, $data, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Return a 405 Method Not Allowed JSON response.
     */
    protected function methodNotAllowed(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->formatResponse($message, $data, Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * Return a 402 Payment Required JSON response.
     */
    protected function paymentRequired(string $message = 'Payment required'): JsonResponse
    {
        return $this->formatResponse($message, null, 402);
    }

    /**
     * Return a 400 Bad Request JSON response.
     */
    protected function badRequest(string $message = 'Bad Request', mixed $data = null): JsonResponse
    {
        return $this->formatResponse($message, $data, 400);
    }
}
