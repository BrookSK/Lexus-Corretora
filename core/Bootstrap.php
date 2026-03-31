<?php
declare(strict_types=1);
namespace LEX\Core;

final class Bootstrap
{
    public static function iniciar(): void
    {
        // Timezone
        $tz = Configuracao::obter('app.timezone', 'America/Sao_Paulo');
        date_default_timezone_set($tz);

        // Error handling
        self::configurarErros();

        // Sessão segura
        self::iniciarSessao();

        // Forçar HTTPS em produção
        if (Configuracao::obter('app.ambiente') === 'producao') {
            if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
                $url = 'https://' . ($_SERVER['HTTP_HOST'] ?? '') . ($_SERVER['REQUEST_URI'] ?? '/');
                header("Location: $url", true, 301);
                exit;
            }
        }

        // Inicializar i18n
        I18n::iniciar();

        // Carregar settings do banco
        try {
            Settings::carregar();
        } catch (\Exception $e) {
            // Banco pode não estar configurado ainda
        }

        // Carregar rotas
        Roteador::carregarRotas();
    }

    private static function iniciarSessao(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) return;

        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'domain'   => '',
            'secure'   => $isHttps,
            'httponly'  => true,
            'samesite'  => 'Lax',
        ]);
        ini_set('session.use_strict_mode', '1');
        ini_set('session.use_only_cookies', '1');
        session_start();
    }

    private static function configurarErros(): void
    {
        $debug = Configuracao::obter('app.debug', false);
        if ($debug) {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        } else {
            error_reporting(E_ALL);
            ini_set('display_errors', '0');
        }

        set_exception_handler(function (\Throwable $e) {
            AppLogger::erro($e->getMessage(), [
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Salvar no banco se possível
            try {
                $pdo = BancoDeDados::obter();
                $errorId = bin2hex(random_bytes(8));
                $stmt = $pdo->prepare("INSERT INTO system_errors (error_id, http_code, type, message, stack_trace, url, ip, user_agent, user_id, created_at) VALUES (:eid, :code, :type, :msg, :trace, :url, :ip, :ua, :uid, NOW())");
                $stmt->execute([
                    'eid'   => $errorId,
                    'code'  => 500,
                    'type'  => get_class($e),
                    'msg'   => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'url'   => $_SERVER['REQUEST_URI'] ?? '',
                    'ip'    => $_SERVER['REMOTE_ADDR'] ?? '',
                    'ua'    => $_SERVER['HTTP_USER_AGENT'] ?? '',
                    'uid'   => Auth::equipeId() ?? Auth::clienteId() ?? Auth::parceiroId(),
                ]);
            } catch (\Exception $dbErr) {
                // Silenciar — já logamos em arquivo
            }

            if (Configuracao::obter('app.debug', false)) {
                echo '<pre>' . htmlspecialchars($e->getMessage() . "\n" . $e->getTraceAsString()) . '</pre>';
            } else {
                $html = View::renderizar(__DIR__ . '/../app/Views/erros/erro.php', [
                    'codigo'   => 500,
                    'mensagem' => I18n::t('erro.interno'),
                    'errorId'  => $errorId ?? null,
                ]);
                http_response_code(500);
                echo $html;
            }
        });
    }
}
