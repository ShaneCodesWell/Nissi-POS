<?php
namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ApiException extends Exception
{
    protected array $errors;

    public function __construct(
        string $message,
        protected int $statusCode = 400,
        array $errors = [],
        ? \Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
        $this->errors = $errors;
    }

    // -------------------------------------------------------------------------
    // Named constructors — one per common failure scenario
    // These keep your service layer readable:
    //   throw ApiException::insufficientStock('Nike Air Force 1 (Red / L)', 5, 12);
    // rather than:
    //   throw new ApiException('Insufficient stock...', 422);
    // -------------------------------------------------------------------------

    /**
     * Stock is not available in the quantity requested.
     */
    public static function insufficientStock(
        string $productName,
        int $available,
        int $requested,
    ): static {
        return new static(
            "Insufficient stock for \"{$productName}\". "
            . "Available: {$available}, requested: {$requested}.",
            422,
            [
                'product'   => $productName,
                'available' => $available,
                'requested' => $requested,
            ]
        );
    }

    /**
     * A discount code is invalid, expired, or has reached its usage limit.
     */
    public static function invalidDiscountCode(string $code, string $reason = ''): static
    {
        return new static(
            "Discount code \"{$code}\" is not valid." . ($reason ? " {$reason}" : ''),
            422,
            ['code' => $code]
        );
    }

    /**
     * A sale cannot be modified because it is no longer in a pending state.
     */
    public static function saleNotEditable(string $saleNumber, string $status): static
    {
        return new static(
            "Sale {$saleNumber} is {$status} and cannot be modified.",
            409,
            ['sale_number' => $saleNumber, 'status' => $status]
        );
    }

    /**
     * A sale cannot be completed because payment is not fully settled.
     */
    public static function paymentIncomplete(string $saleNumber, float $outstanding): static
    {
        return new static(
            "Sale {$saleNumber} cannot be completed. Outstanding balance: {$outstanding}.",
            422,
            ['sale_number' => $saleNumber, 'outstanding' => $outstanding]
        );
    }

    /**
     * A payment amount is invalid for the current sale state.
     */
    public static function invalidPaymentAmount(string $reason): static
    {
        return new static($reason, 422);
    }

    /**
     * A loyalty operation failed — insufficient points, invalid redemption, etc.
     */
    public static function loyaltyError(string $reason): static
    {
        return new static($reason, 422);
    }

    /**
     * A resource was not found within the current organisation scope.
     * Used instead of a 404 abort when you need to throw from a service.
     */
    public static function notFound(string $resource): static
    {
        return new static("{$resource} not found.", 404);
    }

    /**
     * An action is forbidden for the current user or terminal.
     */
    public static function forbidden(string $reason = ''): static
    {
        return new static(
            $reason ?: 'You do not have permission to perform this action.',
            403
        );
    }

    // -------------------------------------------------------------------------
    // Rendering
    // -------------------------------------------------------------------------

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Render the exception as a JSON response.
     * Laravel calls this automatically when it is registered in Handler.php.
     */
    public function render(): JsonResponse
    {
        $payload = [
            'success' => false,
            'message' => $this->getMessage(),
        ];

        if (! empty($this->errors)) {
            $payload['errors'] = $this->errors;
        }

        return response()->json($payload, $this->statusCode);
    }
}
