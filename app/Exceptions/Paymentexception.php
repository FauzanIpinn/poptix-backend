<?php

namespace App\Exceptions;

use RuntimeException;

class PaymentException extends RuntimeException
{
    protected int $statusCode;

    public function __construct(string $message, int $statusCode = 400) {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }

    public function statusCode(): int {
        return $this->statusCode;
    }

    public static function incompletePayload(): self {
        return new self('Payload notifikasi tidak lengkap.', 400);
    }

    public static function invalidSignature(): self {
        return new self('Signature tidak valid.', 403);
    }

    public static function bookingNotFound(): self {
        return new self('Booking tidak ditemukan.', 404);
    }

    public static function amountMismatch(): self {
        return new self('Nominal pembayaran tidak sesuai dengan total booking.', 409);
    }
}