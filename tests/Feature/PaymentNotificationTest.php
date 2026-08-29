<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function makeBooking(): Booking {
        $schedule = Schedule::factory()->create([
            'movie_id' => Movie::factory(),
            'cinema_id' => Cinema::factory(),
            'price' => 50000,
        ]);

        return Booking::factory()->create([
            'user_id' => User::factory(),
            'schedule_id' => $schedule->id,
            'total_price' => 50000,
            'status' => 'pending',
            'midtrans_order_id' => 'PPX-TEST123-1234567890',
        ]);
    }

    protected function signature(string $orderId, string $statusCode, string $grossAmount): string {
        return hash('sha512', $orderId . $statusCode . $grossAmount . config('midtrans.server_key'));
    }

    public function test_notifikasi_valid_menandai_booking_paid(): void {
        $booking = $this->makeBooking();

        $payload = [
            'order_id' => $booking->midtrans_order_id,
            'status_code' => '200',
            'gross_amount' => '50000.00',
            'transaction_status' => 'settlement',
            'fraud_status' => 'accept',
            'payment_type' => 'gopay',
        ];
        $payload['signature_key'] = $this->signature($payload['order_id'], $payload['status_code'], $payload['gross_amount']);

        $this->postJson('/payment/notification', $payload)->assertStatus(200);

        $fresh = $booking->fresh();
        $this->assertEquals('paid', $fresh->status->value ?? $fresh->status);
        $this->assertNotNull($fresh->paid_at);
    }

    public function test_notifikasi_signature_invalid_ditolak(): void {
        $booking = $this->makeBooking();

        $payload = [
            'order_id' => $booking->midtrans_order_id,
            'status_code' => '200',
            'gross_amount' => '50000.00',
            'transaction_status' => 'settlement',
            'signature_key' => 'signature-palsu',
        ];

        $this->postJson('/payment/notification', $payload)->assertStatus(403);

        $fresh = $booking->fresh();
        $this->assertEquals('pending', $fresh->status->value ?? $fresh->status);
    }

    public function test_notifikasi_duplikat_tidak_diproses_ulang(): void {
        $booking = $this->makeBooking();

        $payload = [
            'order_id' => $booking->midtrans_order_id,
            'status_code' => '200',
            'gross_amount' => '50000.00',
            'transaction_status' => 'settlement',
            'fraud_status' => 'accept',
            'payment_type' => 'gopay',
        ];
        $payload['signature_key'] = $this->signature($payload['order_id'], $payload['status_code'], $payload['gross_amount']);

        $this->postJson('/payment/notification', $payload)->assertStatus(200);
        $paidAtFirst = $booking->fresh()->paid_at;

        $this->postJson('/payment/notification', $payload)->assertStatus(200);
        $paidAtSecond = $booking->fresh()->paid_at;

        $this->assertTrue($paidAtFirst->eq($paidAtSecond));
    }
}