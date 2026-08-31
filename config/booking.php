<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Durasi Penguncian Kursi (Reservation Hold)
    |--------------------------------------------------------------------------
    | Berapa lama kursi "ditahan" untuk user sejak booking dibuat, sebelum
    | otomatis di-expire oleh bookings:expire command. Angka ini dipakai
    | BERSAMA oleh BookingService (kolom expires_at) dan MidtransService
    | (durasi Snap token) -- JANGAN pernah hardcode ulang di tempat lain,
    | supaya dua sisi tidak pernah menyimpang.
    */
    'reservation_ttl_minutes' => (int) env('BOOKING_RESERVATION_TTL_MINUTES', 10),

    /*
    |--------------------------------------------------------------------------
    | Batas Jumlah Kursi per Booking
    |--------------------------------------------------------------------------
    */
    'max_seats_per_booking' => (int) env('BOOKING_MAX_SEATS', 6),

    /*
    |--------------------------------------------------------------------------
    | TTL Idempotency Key
    |--------------------------------------------------------------------------
    | Berapa lama kita mengingat idempotency key dari sebuah request booking,
    | untuk mencegah duplikat akibat retry jaringan / double-klik.
    */
    'idempotency_ttl_minutes' => (int) env('BOOKING_IDEMPOTENCY_TTL_MINUTES', 5),

];