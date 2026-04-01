<?php
declare(strict_types=1);
namespace LEX\Core;

use LEX\Core\Http\Resposta;

final class Middlewares
{
    public static function exigirLoginEquipe(): \Closure
    {
        return function () {
            if (!Auth::equipeLogada()) {
                Resposta::redirecionar('/login')->enviar();
                exit;
            }
        };
    }

    public static function exigirLoginCliente(): \Closure
    {
        return function () {
            if (!Auth::clienteLogado()) {
                Resposta::redirecionar('/login')->enviar();
                exit;
            }
        };
    }

    public static function exigirLoginParceiro(): \Closure
    {
        return function () {
            if (!Auth::parceiroLogado()) {
                Resposta::redirecionar('/login')->enviar();
                exit;
            }
        };
    }

    public static function exigirPermissao(string $permissao): \Closure
    {
        return function () use ($permissao) {
            $userId = Auth::equipeId();
            if (!$userId || !Rbac::temPermissao($userId, $permissao)) {
                Resposta::html(View::renderizar(__DIR__ . '/../app/Views/erros/erro.php', [
                    'codigo' => 403,
                    'mensagem' => I18n::t('erro.sem_permissao'),
                ]), 403)->enviar();
                exit;
            }
        };
    }

    public static function rateLimitIp(string $nome, int $max, int $janela): \Closure
    {
        return function () use ($nome, $max, $janela) {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
            $chave = "rl:{$nome}:{$ip}";
            if (!RateLimiter::verificar($chave, $max, $janela)) {
                Resposta::json(['erro' => 'Muitas requisições. Tente novamente em breve.'], 429)->enviar();
                exit;
            }
        };
    }

    public static function rateLimitCliente(string $nome, int $max, int $janela): \Closure
    {
        return function () use ($nome, $max, $janela) {
            $id = Auth::clienteId() ?? 0;
            $chave = "rl:{$nome}:cli:{$id}";
            if (!RateLimiter::verificar($chave, $max, $janela)) {
                Resposta::json(['erro' => 'Muitas requisições.'], 429)->enviar();
                exit;
            }
        };
    }

    public static function rateLimitEquipe(string $nome, int $max, int $janela): \Closure
    {
        return function () use ($nome, $max, $janela) {
            $id = Auth::equipeId() ?? 0;
            $chave = "rl:{$nome}:eq:{$id}";
            if (!RateLimiter::verificar($chave, $max, $janela)) {
                Resposta::json(['erro' => 'Muitas requisições.'], 429)->enviar();
                exit;
            }
        };
    }
}
