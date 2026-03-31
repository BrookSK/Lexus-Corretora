<?php
declare(strict_types=1);
namespace LEX\App\Controllers;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{BancoDeDados, Auth};

final class CookieConsentController
{
    public function salvar(Requisicao $req): Resposta
    {
        $dados = $req->bodyJson();
        try {
            $pdo = BancoDeDados::obter();
            $stmt = $pdo->prepare("INSERT INTO cookie_consents (user_type, user_id, ip, necessary, analytics, marketing, preferences) VALUES (:ut, :uid, :ip, :n, :a, :m, :p)");
            $userType = 'visitante';
            $userId = null;
            if (Auth::equipeLogada()) { $userType = 'equipe'; $userId = Auth::equipeId(); }
            elseif (Auth::clienteLogado()) { $userType = 'cliente'; $userId = Auth::clienteId(); }
            elseif (Auth::parceiroLogado()) { $userType = 'parceiro'; $userId = Auth::parceiroId(); }
            $stmt->execute([
                'ut' => $userType, 'uid' => $userId, 'ip' => $req->ip(),
                'n' => 1, 'a' => (int)($dados['analytics'] ?? 0),
                'm' => (int)($dados['marketing'] ?? 0), 'p' => (int)($dados['preferences'] ?? 0),
            ]);
        } catch (\Exception $e) { /* silenciar */ }
        return Resposta::json(['ok' => true]);
    }
}
