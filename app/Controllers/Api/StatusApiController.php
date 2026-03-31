<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Api;

use LEX\Core\Http\{Requisicao, Resposta};

final class StatusApiController
{
    public function health(Requisicao $req): Resposta
    {
        return Resposta::json(['status' => 'ok']);
    }

    public function status(Requisicao $req): Resposta
    {
        return Resposta::json([
            'status'  => 'ok',
            'version' => '1.0.0',
        ]);
    }
}
