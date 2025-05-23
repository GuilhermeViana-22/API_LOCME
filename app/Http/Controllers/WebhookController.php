<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handleFrontend(Request $request)
    {
        Log::info('[Webhook] Frontend endpoint was hit.');
        return $this->process('/home/bobflows/deploy-frontend.sh', '/home/bobflows/deploy.log');
    }

    public function handleBackend(Request $request)
    {
        return $this->process('/home/bobflows-api/deploy-backend.sh', '/home/bobflows-api/deploy.log');
    }

    private function process(string $scriptPath, string $logPath)
    {
        // Log the request
        Log::info('[Webhook] Endpoint hit, skipping signature validation.');

        // Always run the deploy script
        file_put_contents($logPath, "Deploy triggered at " . now() . "\n", FILE_APPEND);
        exec("$scriptPath >> $logPath 2>&1 &");

        return response('Deployment started', 200);
    }
}
