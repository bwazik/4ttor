<?php

namespace App\Traits;

trait ServiceResponseTrait
{
    /**
     * Generate a success response
     *
     * @param string $message Translation key for success message
     * @param array $data Optional additional data
     * @return array
     */
    protected function successResponse(string $message, array $data = []): array
    {
        return [
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ];
    }

    /**
     * Generate an error response
     *
     * @param string $message Error message
     * @param array $errors Optional detailed errors
     * @return array
     */
    protected function errorResponse(string $message, array $errors = []): array
    {
        return [
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ];
    }

    /**
     * Generate a production-safe error message
     *
     * @param \Exception $exception
     * @return array
     */
    protected function productionErrorResponse(\Exception $exception): array
    {
        return $this->errorResponse(
            config('app.env') === 'production'
                ? trans('main.errorMessage')
                : $exception->getMessage()
        );
    }
}
