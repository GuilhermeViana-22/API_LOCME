<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handleFrontend(Request $request)
    {
        return $this->process($request, '/home/bobflows/deploy-frontend.sh', '/home/bobflows/deploy.log');
    }

    public function handleBackend(Request $request)
    {
        return $this->process($request, '/home/bobflows-api/deploy-backend.sh', '/home/bobflows-api/deploy.log');
    }

    private function process(Request $request, string $scriptPath, string $logPath)
    {
        $secret = env('GITHUB_WEBHOOK_SECRET');
        $payload = $request->getContent();
        $expectedSignature = 'sha256=' . hash_hmac('sha256', $payload, $secret);
        $receivedSignature = $request->header('X-Hub-Signature-256');

        if (!hash_equals($expectedSignature, $receivedSignature)) {
            Log::warning('Invalid webhook signature.', [
                'expected' => $expectedSignature,
                'received' => $receivedSignature,
            ]);
            return response('Invalid signature', 403);
        }

        file_put_contents($logPath, "Deploy triggered at " . now() . "\n", FILE_APPEND);
        exec("$scriptPath >> $logPath 2>&1 &");

        return response('Deployment started', 200);
    }
}
