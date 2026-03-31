<?php
declare(strict_types=1);
namespace LEX\Core\Http;

final class Requisicao
{
    private string $metodo;
    private string $caminho;
    private array $get;
    private array $post;
    private array $servidor;
    private array $cookies;
    private array $arquivos;
    private array $headers;
    private array $params;

    public function __construct()
    {
        $this->metodo   = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $this->caminho  = $this->parseCaminho();
        $this->get      = $_GET;
        $this->post     = $_POST;
        $this->servidor = $_SERVER;
        $this->cookies  = $_COOKIE;
        $this->arquivos = $_FILES;
        $this->headers  = $this->parseHeaders();
        $this->params   = [];
    }

    private function parseCaminho(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $caminho = parse_url($uri, PHP_URL_PATH) ?: '/';
        return '/' . trim($caminho, '/');
    }

    private function parseHeaders(): array
    {
        $headers = [];
        foreach ($_SERVER as $chave => $valor) {
            if (str_starts_with($chave, 'HTTP_')) {
                $nome = str_replace('_', '-', strtolower(substr($chave, 5)));
                $headers[$nome] = $valor;
            }
        }
        return $headers;
    }

    public function metodo(): string { return $this->metodo; }
    public function caminho(): string { return $this->caminho; }
    public function get(string $chave, mixed $padrao = null): mixed { return $this->get[$chave] ?? $padrao; }
    public function post(string $chave, mixed $padrao = null): mixed { return $this->post[$chave] ?? $padrao; }
    public function todosPost(): array { return $this->post; }
    public function todosGet(): array { return $this->get; }
    public function header(string $nome, mixed $padrao = null): mixed { return $this->headers[strtolower($nome)] ?? $padrao; }
    public function cookie(string $nome, mixed $padrao = null): mixed { return $this->cookies[$nome] ?? $padrao; }
    public function arquivo(string $nome): ?array { return $this->arquivos[$nome] ?? null; }
    public function arquivos(): array { return $this->arquivos; }
    public function ip(): string { return $this->servidor['REMOTE_ADDR'] ?? '0.0.0.0'; }
    public function userAgent(): string { return $this->servidor['HTTP_USER_AGENT'] ?? ''; }
    public function isAjax(): bool { return $this->header('x-requested-with') === 'XMLHttpRequest'; }
    public function isJson(): bool { return str_contains($this->header('content-type', ''), 'application/json'); }

    public function definirParams(array $params): void { $this->params = $params; }
    public function param(string $chave, mixed $padrao = null): mixed { return $this->params[$chave] ?? $padrao; }

    public function bodyJson(): array
    {
        $corpo = file_get_contents('php://input');
        if (empty($corpo)) return [];
        $dados = json_decode($corpo, true);
        return is_array($dados) ? $dados : [];
    }
}
