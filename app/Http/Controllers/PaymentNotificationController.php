<?php

namespace App\Http\Controllers;

use App\Services\MidtransService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentNotificationController extends Controller
{
    public function __construct(protected MidtransService $midtransService) {}

    /**
     * Menangani notifikasi webhook dari Midtrans.
     *
     * Seluruh logika bisnis (verifikasi, update status) didelegasikan
     * sepenuhnya ke MidtransService.
     */
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();

        try {
            $this->midtransService->handleWebhookPayload($payload);

            return response()->json(['message' => 'Notifikasi berhasil diproses.']);
        } catch (\InvalidArgumentException $e) {
            // Payload tidak lengkap
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            // Signature tidak valid (abort 403 dari service)
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        } catch (ModelNotFoundException $e) {
            // Booking tidak ditemukan
            return response()->json(['message' => 'Booking tidak ditemukan.'], 404);
        } catch (\Throwable $e) {
            // Tangkap error tak terduga agar webhook tidak gagal diam-diam
            Log::error('Midtrans webhook: error tidak terduga.', [
                'error'   => $e->getMessage(),
                'payload' => $payload,
            ]);
            return response()->json(['message' => 'Terjadi kesalahan pada server.'], 500);
        }
    }
}