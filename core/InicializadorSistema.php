<?php
declare(strict_types=1);
namespace LEX\Core;

final class InicializadorSistema
{
    public static function executar(): array
    {
        $resultados = [];

        // 1. Criar diretórios necessários
        $dirs = [
            __DIR__ . '/../storage/logs',
            __DIR__ . '/../storage/backups',
            __DIR__ . '/../storage/uploads',
            __DIR__ . '/../storage/exports',
            __DIR__ . '/../storage/temp',
            __DIR__ . '/../public/assets/uploads',
        ];
        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
                $resultados[] = "Diretório criado: $dir";
            }
        }

        // 2. Executar schema.sql se tabelas não existem
        $pdo = BancoDeDados::obter();
        $tabelas = $pdo->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);
        if (empty($tabelas)) {
            $schema = __DIR__ . '/../database/schema.sql';
            if (file_exists($schema)) {
                $sql = file_get_contents($schema);
                $pdo->exec($sql);
                $resultados[] = 'Schema inicial executado.';
            }
        }

        // 3. Executar migrations pendentes
        self::executarMigrations($pdo, $resultados);

        // 4. Seeds iniciais
        self::seedsIniciais($pdo, $resultados);

        return $resultados;
    }

    private static function executarMigrations(\PDO $pdo, array &$resultados): void
    {
        // Garantir tabela de migrations
        $pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            file_name VARCHAR(255) NOT NULL UNIQUE,
            executed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $dir = __DIR__ . '/../database/migrations';
        if (!is_dir($dir)) return;

        $executadas = $pdo->query("SELECT file_name FROM migrations")->fetchAll(\PDO::FETCH_COLUMN);
        $arquivos = glob($dir . '/*.sql');
        sort($arquivos);

        foreach ($arquivos as $arquivo) {
            $nome = basename($arquivo);
            if (in_array($nome, $executadas, true)) continue;
            $sql = file_get_contents($arquivo);
            $pdo->exec($sql);
            $stmt = $pdo->prepare("INSERT INTO migrations (file_name) VALUES (:f)");
            $stmt->execute(['f' => $nome]);
            $resultados[] = "Migration executada: $nome";
        }
    }

    private static function seedsIniciais(\PDO $pdo, array &$resultados): void
    {
        // Criar roles padrão se não existem
        $count = (int)$pdo->query("SELECT COUNT(*) FROM roles")->fetchColumn();
        if ($count === 0) {
            $roles = [
                ['superadmin', 'Super Administrador'],
                ['admin', 'Administrador'],
                ['operador', 'Operador'],
                ['comercial', 'Comercial'],
            ];
            $stmt = $pdo->prepare("INSERT INTO roles (slug, name, created_at) VALUES (:s, :n, NOW())");
            foreach ($roles as [$slug, $name]) {
                $stmt->execute(['s' => $slug, 'n' => $name]);
            }
            $resultados[] = 'Roles padrão criadas.';
        }

        // Settings padrão
        $defaults = [
            'sistema.nome'           => 'Lexus Corretora',
            'sistema.copyright'      => '© ' . date('Y') . ' Lexus — Estruturação Estratégica de Obras',
            'seo.meta_title'         => 'Lexus Corretora — Estruturação Estratégica de Obras',
            'seo.meta_description'   => 'Conectamos clientes a uma rede qualificada de parceiros para obras e reformas.',
            'contato.email'          => 'contato@lexuscorretora.com.br',
            'billing.ativo'          => '0',
            'billing.taxa_conversao_usd' => '5.0',
            'stripe.mode'            => 'sandbox',
            'asaas.mode'             => 'sandbox',
            'comissao.percentual_padrao' => '5',
            'matching.ativo'         => '1',
            'vetriks.ativo'          => '1',
        ];
        foreach ($defaults as $chave => $valor) {
            $existing = Settings::obter($chave);
            if ($existing === null) {
                Settings::definir($chave, $valor);
            }
        }
        $resultados[] = 'Settings padrão verificadas.';
    }
}
