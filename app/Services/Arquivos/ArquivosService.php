<?php
declare(strict_types=1);
namespace LEX\App\Services\Arquivos;

use LEX\Core\BancoDeDados;
use LEX\Core\ConfiguracoesSistema;
use PDO;

final class ArquivosService
{
    public static function upload(array $arquivo, string $entidade, int $entidadeId, string $tipo = 'geral'): array
    {
        if (!self::validarArquivo($arquivo)) {
            throw new \RuntimeException('Arquivo inválido: tipo não permitido ou tamanho excedido.');
        }

        $ext = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
        $nomeUnico = sprintf('%s_%d_%s.%s', $entidade, $entidadeId, bin2hex(random_bytes(8)), $ext);
        // Resolve o diretório public/ a partir do local deste arquivo (app/Services/Arquivos/)
        $publicDir = dirname(__DIR__, 3) . '/public';
        $diretorio = $publicDir . '/uploads/' . $entidade . '/' . date('Y/m');

        if (!is_dir($diretorio)) {
            mkdir($diretorio, 0755, true);
        }

        $destino = $diretorio . '/' . $nomeUnico;
        if (!move_uploaded_file($arquivo['tmp_name'], $destino)) {
            throw new \RuntimeException('Falha ao mover arquivo enviado.');
        }

        $relativePath = 'uploads/' . $entidade . '/' . date('Y/m') . '/' . $nomeUnico;

        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "INSERT INTO anexos_gerais (entity_type, entity_id, name, file_path, file_size, mime_type)
             VALUES (:entity_type, :entity_id, :name, :file_path, :file_size, :mime_type)"
        );
        $stmt->execute([
            'entity_type' => $entidade,
            'entity_id' => $entidadeId,
            'name' => $arquivo['name'],
            'file_path' => $relativePath,
            'file_size' => $arquivo['size'],
            'mime_type' => $arquivo['type'] ?? mime_content_type($destino),
        ]);

        $id = (int)$pdo->lastInsertId();

        return [
            'id' => $id,
            'name' => $arquivo['name'],
            'file_path' => $relativePath,
            'file_size' => $arquivo['size'],
            'mime_type' => $arquivo['type'] ?? mime_content_type($destino),
        ];
    }

    public static function listarPorEntidade(string $entidade, int $entidadeId): array
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "SELECT id, name, file_path, file_size, mime_type, created_at
             FROM anexos_gerais
             WHERE entity_type = :entity_type AND entity_id = :entity_id
             ORDER BY created_at DESC"
        );
        $stmt->execute(['entity_type' => $entidade, 'entity_id' => $entidadeId]);
        return $stmt->fetchAll();
    }

    public static function obterPorId(int $id): ?array
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare("SELECT * FROM anexos_gerais WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function excluir(int $id): bool
    {
        $pdo = BancoDeDados::obter();
        $arquivo = self::obterPorId($id);
        if (!$arquivo) {
            return false;
        }

        $fullPath = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '.', '/') . '/' . $arquivo['file_path'];
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        $stmt = $pdo->prepare("DELETE FROM anexos_gerais WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public static function validarArquivo(array $arquivo): bool
    {
        if (empty($arquivo['tmp_name']) || $arquivo['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $maxSize = ConfiguracoesSistema::uploadMaxSize();
        if ($arquivo['size'] > $maxSize) {
            return false;
        }

        $ext = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
        $allowedTypes = ConfiguracoesSistema::uploadAllowedTypes();
        $allowedTypes = array_map('trim', $allowedTypes);

        if (!in_array($ext, $allowedTypes, true)) {
            return false;
        }

        return true;
    }
}
