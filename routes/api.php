<?php
declare(strict_types=1);

use LEX\Core\Roteador;
use LEX\App\Controllers\Api\StatusApiController;
use LEX\App\Controllers\Webhooks\StripeWebhookController;
use LEX\App\Controllers\Webhooks\AsaasWebhookController;

// API Status / Health
Roteador::get('/api/health', [StatusApiController::class, 'health']);
Roteador::get('/api/status', [StatusApiController::class, 'status']);

// Webhooks (sem CSRF)
Roteador::post('/webhooks/stripe', [StripeWebhookController::class, 'handle'], [], true);
Roteador::post('/webhooks/asaas', [AsaasWebhookController::class, 'handle'], [], true);
