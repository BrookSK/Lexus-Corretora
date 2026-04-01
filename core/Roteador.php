<?php
declare(strict_types=1);
namespace LEX\Core;

use LEX\Core\Http\Requisicao;
use LEX\Core\Http\Resposta;

final class Roteador
{
    private static array $rotas = [];
    private static array $rotasWebhook = [];

    public static function get(string $caminho, array $handler, array $middlewares = []): void
    {
        self::$rotas['GET'][$caminho] = ['handler' => $handler, 'middlewares' => $middlewares];
    }

    public static function post(string $caminho, array $handler, array $middlewares = [], bool $webhook = false): void
    {
        self::$rotas['POST'][$caminho] = ['handler' => $handler, 'middlewares' => $middlewares];
        if ($webhook) {
            self::$rotasWebhook[] = $caminho;
        }
    }

    public static function despachar(): void
    {
        $req = new Requisicao();
        $metodo = $req->metodo();
        $caminho = $req->caminho();

        // Normalizar caminho
        if ($caminho !== '/' && str_ends_with($caminho, '/')) {
            $caminho = rtrim($caminho, '/');
        }

        // CSRF automático em POST (exceto webhooks)
        if ($metodo === 'POST' && !in_array($caminho, self::$rotasWebhook, true)) {
            if (!Csrf::validarRequisicao()) {
                // Se for requisição AJAX, retorna JSON
                $isAjax = ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest'
                       || str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json');
                if ($isAjax) {
                    Resposta::json(['erro' => 'Sessão expirada. Recarregue a página.'], 403)->enviar();
                    return;
                }
                // Para formulários normais: redireciona de volta com mensagem
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Sessão expirada. Por favor, tente novamente.'];
                $voltar = $_SERVER['HTTP_REFERER'] ?? '/';
                Resposta::redirecionar($voltar)->enviar();
                return;
            }
        }

        // Buscar rota exata
        if (isset(self::$rotas[$metodo][$caminho])) {
            self::executar(self::$rotas[$metodo][$caminho], $req);
            return;
        }

        // Buscar rota com parâmetros
        foreach (self::$rotas[$metodo] ?? [] as $padrao => $config) {
            $regex = self::caminhoParaRegex($padrao);
            if (preg_match($regex, $caminho, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $req->definirParams($params);
                self::executar($config, $req);
                return;
            }
        }

        // 404
        $html = View::renderizar(__DIR__ . '/../app/Views/erros/erro.php', [
            'codigo' => 404,
            'mensagem' => I18n::t('erro.pagina_nao_encontrada'),
        ]);
        Resposta::html($html, 404)->enviar();
    }

    private static function caminhoParaRegex(string $caminho): string
    {
        $regex = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $caminho);
        return '#^' . $regex . '$#';
    }

    private static function executar(array $config, Requisicao $req): void
    {
        // Executar middlewares
        foreach ($config['middlewares'] as $middleware) {
            if (is_callable($middleware)) {
                $middleware($req);
            }
        }

        [$classe, $metodo] = $config['handler'];
        $controller = new $classe();
        $resposta = $controller->$metodo($req);

        if ($resposta instanceof Resposta) {
            $resposta->enviar();
        }
    }

    public static function carregarRotas(): void
    {
        $roteador = new self();
        $webFile = __DIR__ . '/../routes/web.php';
        $apiFile = __DIR__ . '/../routes/api.php';
        if (file_exists($webFile)) require $webFile;
        if (file_exists($apiFile)) require $apiFile;
    }
}
