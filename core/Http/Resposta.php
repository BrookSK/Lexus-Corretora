<?php
declare(strict_types=1);
namespace LEX\Core\Http;

final class Resposta
{
    private string $corpo;
    private int $status;
    private array $headers;

    private function __construct(string $corpo, int $status = 200, array $headers = [])
    {
        $this->corpo   = $corpo;
        $this->status  = $status;
        $this->headers = $headers;
    }

    public static function html(string $html, int $status = 200): self
    {
        return new self($html, $status, ['Content-Type' => 'text/html; charset=utf-8']);
    }

    public static function json(mixed $dados, int $status = 200): self
    {
        return new self(
            json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            $status,
            ['Content-Type' => 'application/json; charset=utf-8']
        );
    }

    public static function texto(string $texto, int $status = 200): self
    {
        return new self($texto, $status, ['Content-Type' => 'text/plain; charset=utf-8']);
    }

    public static function xml(string $xml, int $status = 200): self
    {
        return new self($xml, $status, ['Content-Type' => 'application/xml; charset=utf-8']);
    }

    public static function redirecionar(string $url, int $status = 302): self
    {
        return new self('', $status, ['Location' => $url]);
    }

    public static function download(string $conteudo, string $nomeArquivo, string $mime = 'application/octet-stream'): self
    {
        return new self($conteudo, 200, [
            'Content-Type'        => $mime,
            'Content-Disposition' => 'attachment; filename="' . $nomeArquivo . '"',
        ]);
    }

    public function comHeaders(array $headers): self
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    public function enviar(): void
    {
        http_response_code($this->status);
        foreach ($this->headers as $nome => $valor) {
            header("$nome: $valor");
        }
        echo $this->corpo;
    }
}
