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

    public function handle(Request $request): JsonResponse {
        $payload = $request->all();

        try {
            $this->midtransService->handleNotification($payload);

            return response()->json(['message' => 'Notifikasi berhasil diproses.']);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Booking tidak ditemukan.'], 404);
        } catch (\Throwable $e) {
            Log::error('Midtrans webhook: error tidak terduga.', [
                'error'   => $e->getMessage(),
                'payload' => $payload,
            ]);
            return response()->json(['message' => 'Terjadi kesalahan pada server.'], 500);
        }
    }
}