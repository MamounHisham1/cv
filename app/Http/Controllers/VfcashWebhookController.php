<?php

namespace App\Http\Controllers;

use App\Services\VfcashService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class VfcashWebhookController extends Controller
{
    public function __invoke(Request $request, VfcashService $vfcash): Response
    {
        $payload = $request->getContent();
        $signature = $request->header('X-VFCash-Signature');

        if (! $signature || ! $vfcash->verifySignature($payload, $signature)) {
            Log::warning('VFCash webhook: invalid signature');

            return response('Invalid signature', 401);
        }

        $data = $request->all();
        $event = $data['event'] ?? null;
        $paymentData = $data['data'] ?? $data;

        Log::info('VFCash webhook received', ['event' => $event, 'id' => $data['id'] ?? null]);

        match ($event) {
            'payment.confirmed' => $vfcash->handleConfirmed($paymentData),
            'payment.expired' => $vfcash->handleExpired($paymentData),
            'payment.cancelled' => $vfcash->handleCancelled($paymentData),
            default => Log::info('VFCash webhook: unhandled event', ['event' => $event]),
        };

        return response('OK', 200);
    }
}
