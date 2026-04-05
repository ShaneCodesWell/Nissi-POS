<?php
namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Exceptions that should never be reported to logs.
     * Validation errors and auth failures are expected — no need to log them.
     */
    protected $dontReport = [
        ApiException::class,
        ValidationException::class,
        AuthenticationException::class,
    ];

    /**
     * Exceptions whose context should not be included in logs.
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Add any custom reporting here (e.g. Sentry, Bugsnag)
            // $this->reportToSentry($e);
        });
    }

    // -------------------------------------------------------------------------
    // JSON response rendering
    // -------------------------------------------------------------------------

    public function render($request, Throwable $e): \Illuminate\Http\Response  | JsonResponse | \Illuminate\Http\RedirectResponse
    {
        if ($this->isApiRequest($request)) {
            return $this->renderJson($request, $e);
        }

        return parent::render($request, $e);
    }

    protected function isApiRequest(Request $request): bool
    {
        return $request->is('api/*')
        || $request->expectsJson()
        || $request->wantsJson();
    }

    /**
     * Convert any exception into a clean, structured JSON response.
     * Each exception type maps to a specific HTTP status and message shape.
     */
    protected function renderJson(Request $request, Throwable $e): JsonResponse
    {
        // ── Our own intentional API errors ────────────────────────────────────
        if ($e instanceof ApiException) {
            return $e->render();
        }

        // ── Laravel validation errors ─────────────────────────────────────────
        // Returns 422 with a field-by-field errors map
        if ($e instanceof ValidationException) {
            return response()->json([
                'success' => false,
                'message' => 'The given data was invalid.',
                'errors'  => $e->errors(),
            ], 422);
        }

        // ── Model not found (Eloquent route binding) ──────────────────────────
        // e.g. GET /products/9999 where product 9999 doesn't exist
        if ($e instanceof ModelNotFoundException) {
            $model = class_basename($e->getModel());
            return response()->json([
                'success' => false,
                'message' => "{$model} not found.",
            ], 404);
        }

        // ── Route not found ───────────────────────────────────────────────────
        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'The requested endpoint does not exist.',
            ], 404);
        }

        // ── HTTP method not allowed ───────────────────────────────────────────
        // e.g. POST to a GET-only route
        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'Method not allowed.',
            ], 405);
        }

        // ── Unauthenticated ───────────────────────────────────────────────────
        if ($e instanceof AuthenticationException) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated. Please log in.',
            ], 401);
        }

        // ── Symfony HTTP exceptions (abort() calls) ───────────────────────────
        // Covers abort(403), abort(404), abort(429), etc.
        if ($e instanceof HttpException) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: $this->httpStatusMessage($e->getStatusCode()),
            ], $e->getStatusCode());
        }

        // ── Service layer RuntimeExceptions ──────────────────────────────────
        // Services throw \RuntimeException for controlled business failures.
        // We surface the message but return 422 Unprocessable Entity.
        if ($e instanceof \RuntimeException) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        // ── InvalidArgumentException ──────────────────────────────────────────
        // Services throw this for bad input that slipped past validation.
        if ($e instanceof \InvalidArgumentException) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        // ── Anything else — unexpected crash ──────────────────────────────────
        // Never expose the real message or stack trace in production.
        // In local/development environments we surface the real error.
        if (app()->environment('local', 'testing')) {
            return response()->json([
                'success'   => false,
                'message'   => $e->getMessage(),
                'exception' => get_class($e),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'trace'     => collect($e->getTrace())->take(8)->toArray(),
            ], 500);
        }

        return response()->json([
            'success' => false,
            'message' => 'An unexpected error occurred. Please try again.',
        ], 500);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Map common HTTP status codes to human-readable messages.
     * Used as a fallback when abort() is called without a message.
     */
    protected function httpStatusMessage(int $statusCode): string
    {
        return match ($statusCode) {
            400     => 'Bad request.',
            401     => 'Unauthenticated.',
            403     => 'You do not have permission to perform this action.',
            404     => 'Resource not found.',
            405     => 'Method not allowed.',
            408     => 'Request timeout.',
            409     => 'Conflict — the resource is in an incompatible state.',
            422     => 'Unprocessable request.',
            429     => 'Too many requests. Please slow down.',
            500     => 'Internal server error.',
            503     => 'Service unavailable.',
            default => 'Something went wrong.',
        };
    }
}
