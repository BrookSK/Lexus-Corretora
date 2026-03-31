<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Webhooks;

use LEX\Core\Http\{Requisicao, Resposta};

final class StripeWebhookController
{
    public function handle(Requisicao $req): Resposta
    {
        // TODO: Implementar processamento do webhook Stripe
        return Resposta::json(['status' => 'ok']);
    }
}
