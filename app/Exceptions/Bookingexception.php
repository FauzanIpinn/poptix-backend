<?php

namespace App\Exceptions;

use RuntimeException;

class BookingException extends RuntimeException
{
    protected int $statusCode;

    public function __construct(string $message, int $statusCode = 409) {
        parent::__construct($message);

        $this->statusCode = $statusCode;
    }

    public function statusCode(): int {
        return $this->statusCode;
    }

    public static function seatInvalid(): self {
        return new self('Kursi yang dipilih tidak valid atau sudah dibooking.', 422);
    }

    public static function seatNotInStudio(): self {
        return new self("Salah satu kursi yang dipilih tidak tersedia untuk jadwal ini.", 422);
    }

    public static function seatAlreadyTaken(): self {
        return new self('Salah satu kursi yang kamu pilih baru saja dipesan orang lain.', 409);
    }
 
    public static function gatewayFailure(): self {
        return new self('Gagal menginisiasi pembayaran. Silakan coba lagi.', 502);
    }
 
    public static function alreadyPaid(): self {
        return new self('Booking ini sudah dibayar.', 409);
    }
 
    public static function notPending(): self {
        return new self('Booking ini sudah tidak bisa dibayar.', 409);
    }
 
    public static function expired(): self {
        return new self('Waktu pembayaran booking ini sudah habis.', 410);
    }
}
