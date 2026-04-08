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

    public static function exigirPerfilCompletoParceiro(): \Closure
    {
        return function () {
            if (!Auth::parceiroLogado()) {
                Resposta::redirecionar('/login')->enviar();
                exit;
            }
            
            // Verificar se o perfil está completo
            $parceiroId = Auth::parceiroId();
            $pdo = BancoDeDados::obter();
            $stmt = $pdo->prepare(
                "SELECT `type`, fantasy_name, state_registration, estado, cidade, address, 
                        specialties, experience_years, team_size, monthly_capacity, description,
                        certifications, `references`, website
                 FROM parceiros WHERE id = ?"
            );
            $stmt->execute([$parceiroId]);
            $parceiro = $stmt->fetch();
            
            if (!$parceiro) {
                Resposta::redirecionar('/login')->enviar();
                exit;
            }
            
            // Verificar se campos essenciais estão preenchidos
            $camposObrigatorios = [
                'type', 'fantasy_name', 'state_registration', 'estado', 'cidade', 'address',
                'specialties', 'experience_years', 'team_size', 'monthly_capacity', 'description',
                'certifications', 'references', 'website'
            ];
            
            $perfilIncompleto = false;
            foreach ($camposObrigatorios as $campo) {
                if (empty($parceiro[$campo])) {
                    $perfilIncompleto = true;
                    break;
                }
            }
            
            // Se perfil incompleto, redirecionar para completar
            if ($perfilIncompleto) {
                $caminhoAtual = $_SERVER['REQUEST_URI'] ?? '';
                // Permitir acesso apenas à página de perfil e logout
                if (!str_contains($caminhoAtual, '/parceiro/perfil') && 
                    !str_contains($caminhoAtual, '/parceiro/sair') &&
                    !str_contains($caminhoAtual, '/parceiro/minha-conta')) {
                    $_SESSION['flash'] = [
                        'type' => 'warning',
                        'message' => 'Complete seu perfil para acessar o sistema.'
                    ];
                    Resposta::redirecionar('/parceiro/perfil')->enviar();
                    exit;
                }
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
