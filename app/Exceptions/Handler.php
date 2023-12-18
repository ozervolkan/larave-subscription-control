<?php

namespace App\Exceptions;

use App\Helpers\JsonResponseHelper;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use Illuminate\Http\JsonResponse;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Exception|Throwable $e): JsonResponse
    {
        if ($e instanceof QueryException) {
            return JsonResponseHelper::error('Veritabanı hatası', 500);
        }

        if ($e instanceof HttpException) {
            return JsonResponseHelper::error('Bir HTTP hatası oluştu', $e->getStatusCode());
        }

        if ($e instanceof ValidationException) {
            $errors = $e->errors();

            return response()->json([
                'success'=> false,
                'message' => 'Doğrulama hatası.',
                'validation_errors' => $errors,
            ], 422);
        }
        return JsonResponseHelper::error('Bir hata oluştu.', 500);
    }
}
