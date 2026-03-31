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
        $stmtTables = $pdo->query("SHOW TABLES");
        $tabelas = $stmtTables->fetchAll(\PDO::FETCH_COLUMN);
        $stmtTables->closeCursor();
        if (empty($tabelas)) {
            $schema = __DIR__ . '/../database/schema.sql';
            if (file_exists($schema)) {
                self::executarSqlMulti($pdo, file_get_contents($schema));
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

        $stmtExec = $pdo->query("SELECT file_name FROM migrations");
        $executadas = $stmtExec->fetchAll(\PDO::FETCH_COLUMN);
        $stmtExec->closeCursor();
        $arquivos = glob($dir . '/*.sql');
        sort($arquivos);

        foreach ($arquivos as $arquivo) {
            $nome = basename($arquivo);
            if (in_array($nome, $executadas, true)) continue;
            $sql = file_get_contents($arquivo);
            self::executarSqlMulti($pdo, $sql);
            $stmt = $pdo->prepare("INSERT INTO migrations (file_name) VALUES (:f)");
            $stmt->execute(['f' => $nome]);
            $stmt->closeCursor();
            $resultados[] = "Migration executada: $nome";
        }
    }

    private static function executarSqlMulti(\PDO $pdo, string $sql): void
    {
        // Separar statements e executar um por um para evitar unbuffered query errors
        $statements = array_filter(
            array_map('trim', explode(';', $sql)),
            fn(string $s) => $s !== '' && !str_starts_with($s, '--')
        );
        foreach ($statements as $statement) {
            if (empty(trim($statement))) continue;
            try {
                $pdo->exec($statement);
            } catch (\PDOException $e) {
                // Ignorar erros de "already exists" e similares
                if (!str_contains($e->getMessage(), 'already exists') && !str_contains($e->getMessage(), 'Duplicate')) {
                    throw $e;
                }
            }
        }
    }

    private static function seedsIniciais(\PDO $pdo, array &$resultados): void
    {
        // Criar roles padrão se não existem
        $stmt = $pdo->query("SELECT COUNT(*) FROM roles");
        $count = (int)$stmt->fetchColumn();
        $stmt->closeCursor();
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

        // Textos legais padrão
        self::seedTextosLegais($resultados);

        $resultados[] = 'Settings padrão verificadas.';
    }

    private static function seedTextosLegais(array &$resultados): void
    {
        $termos = Settings::obter('legal.termos');
        if ($termos === null || $termos === '<p>Termos de uso em elaboração.</p>') {
            Settings::definir('legal.termos', self::termosDefault());
            $resultados[] = 'Termos de uso padrão criados.';
        }

        $privacidade = Settings::obter('legal.privacidade');
        if ($privacidade === null || $privacidade === '<p>Política de privacidade em elaboração.</p>') {
            Settings::definir('legal.privacidade', self::privacidadeDefault());
            $resultados[] = 'Política de privacidade padrão criada.';
        }
    }

    private static function termosDefault(): string
    {
        return <<<'HTML'
<h2 style="font-family:'Cormorant Garamond',serif;font-weight:300;font-size:1.8rem;margin-bottom:24px;color:#B8945A">Termos de Uso</h2>
<p style="font-size:.85rem;color:rgba(12,12,10,.45);margin-bottom:32px">Última atualização: março de 2026</p>

<h3 style="font-size:1rem;font-weight:500;margin:32px 0 12px">1. Aceitação dos Termos</h3>
<p>Ao acessar e utilizar a plataforma Lexus Corretora ("Plataforma"), você concorda com estes Termos de Uso. Caso não concorde com qualquer disposição, solicitamos que não utilize nossos serviços.</p>

<h3 style="font-size:1rem;font-weight:500;margin:32px 0 12px">2. Sobre a Lexus Corretora</h3>
<p>A Lexus Corretora é uma plataforma de estruturação, conexão e repasse de oportunidades de obras e reformas. Atuamos como corretora estratégica entre clientes finais e empresas/profissionais executores.</p>
<p><strong>A Lexus não executa obras, não intermedia financeiramente contratos e não assume responsabilidade pela execução dos serviços contratados entre as partes.</strong></p>

<h3 style="font-size:1rem;font-weight:500;margin:32px 0 12px">3. Cadastro e Conta</h3>
<p>Para utilizar determinadas funcionalidades da Plataforma, é necessário criar uma conta fornecendo informações verdadeiras, completas e atualizadas. Você é responsável pela confidencialidade de suas credenciais de acesso e por todas as atividades realizadas em sua conta.</p>

<h3 style="font-size:1rem;font-weight:500;margin:32px 0 12px">4. Serviços da Plataforma</h3>
<p>A Lexus oferece os seguintes serviços:</p>
<ul style="margin:12px 0 12px 24px;line-height:1.8">
<li>Captação e estruturação de demandas de obras e reformas</li>
<li>Conexão entre clientes e parceiros qualificados</li>
<li>Distribuição de oportunidades para parceiros elegíveis</li>
<li>Coleta e organização de propostas</li>
<li>Apoio à comparação e seleção de propostas</li>
<li>Acompanhamento do pipeline comercial</li>
</ul>

<h3 style="font-size:1rem;font-weight:500;margin:32px 0 12px">5. Responsabilidades do Usuário</h3>
<p>O usuário se compromete a: fornecer informações verídicas; não utilizar a Plataforma para fins ilícitos; respeitar os direitos de propriedade intelectual; não tentar acessar áreas restritas sem autorização; manter seus dados cadastrais atualizados.</p>

<h3 style="font-size:1rem;font-weight:500;margin:32px 0 12px">6. Limitação de Responsabilidade</h3>
<p>A Lexus não se responsabiliza por: qualidade, prazo ou resultado da execução de obras contratadas entre clientes e parceiros; inadimplência de qualquer das partes; danos diretos ou indiretos decorrentes da relação comercial entre cliente e parceiro; indisponibilidade temporária da Plataforma.</p>

<h3 style="font-size:1rem;font-weight:500;margin:32px 0 12px">7. Propriedade Intelectual</h3>
<p>Todo o conteúdo da Plataforma, incluindo textos, imagens, logotipos, marcas e software, é de propriedade da Lexus Corretora ou de seus licenciadores, protegido pelas leis de propriedade intelectual aplicáveis.</p>

<h3 style="font-size:1rem;font-weight:500;margin:32px 0 12px">8. Comissão de Repasse</h3>
<p>A Lexus pode receber uma comissão de repasse pela intermediação comercial entre clientes e parceiros. Os termos específicos de comissão são acordados individualmente com cada parceiro.</p>

<h3 style="font-size:1rem;font-weight:500;margin:32px 0 12px">9. Modificações</h3>
<p>A Lexus reserva-se o direito de modificar estes Termos a qualquer momento. As alterações entram em vigor a partir de sua publicação na Plataforma. O uso continuado após alterações constitui aceitação dos novos termos.</p>

<h3 style="font-size:1rem;font-weight:500;margin:32px 0 12px">10. Foro</h3>
<p>Estes Termos são regidos pelas leis da República Federativa do Brasil. Fica eleito o foro da comarca de São Paulo/SP para dirimir quaisquer controvérsias.</p>

<h3 style="font-size:1rem;font-weight:500;margin:32px 0 12px">11. Contato</h3>
<p>Para dúvidas sobre estes Termos, entre em contato pelo e-mail: <a href="mailto:contato@lexuscorretora.com.br" style="color:#B8945A">contato@lexuscorretora.com.br</a></p>
HTML;
    }

    private static function privacidadeDefault(): string
    {
        return <<<'HTML'
<h2 style="font-family:'Cormorant Garamond',serif;font-weight:300;font-size:1.8rem;margin-bottom:24px;color:#B8945A">Política de Privacidade</h2>
<p style="font-size:.85rem;color:rgba(12,12,10,.45);margin-bottom:32px">Última atualização: março de 2026</p>

<h3 style="font-size:1rem;font-weight:500;margin:32px 0 12px">1. Introdução</h3>
<p>A Lexus Corretora ("Lexus", "nós") valoriza a privacidade de seus usuários. Esta Política de Privacidade descreve como coletamos, usamos, armazenamos e protegemos suas informações pessoais, em conformidade com a Lei Geral de Proteção de Dados (LGPD — Lei nº 13.709/2018).</p>

<h3 style="font-size:1rem;font-weight:500;margin:32px 0 12px">2. Dados Coletados</h3>
<p>Podemos coletar os seguintes dados pessoais:</p>
<ul style="margin:12px 0 12px 24px;line-height:1.8">
<li><strong>Dados de identificação:</strong> nome, e-mail, telefone, CPF/CNPJ, endereço</li>
<li><strong>Dados profissionais:</strong> empresa, cargo, especialidades, portfólio, certificações (para parceiros)</li>
<li><strong>Dados de uso:</strong> páginas visitadas, tempo de navegação, endereço IP, tipo de navegador</li>
<li><strong>Dados de comunicação:</strong> mensagens trocadas na plataforma, e-mails enviados</li>
<li><strong>Dados de projeto:</strong> informações sobre obras, plantas, projetos, fotos enviadas</li>
</ul>

<h3 style="font-size:1rem;font-weight:500;margin:32px 0 12px">3. Finalidade do Tratamento</h3>
<p>Utilizamos seus dados para: prestação dos serviços da plataforma; comunicação sobre demandas e oportunidades; qualificação de parceiros; envio de notificações relevantes; melhoria contínua da plataforma; cumprimento de obrigações legais; segurança e prevenção de fraudes.</p>

<h3 style="font-size:1rem;font-weight:500;margin:32px 0 12px">4. Base Legal</h3>
<p>O tratamento de dados pessoais pela Lexus é fundamentado nas seguintes bases legais da LGPD: consentimento do titular; execução de contrato; legítimo interesse; cumprimento de obrigação legal.</p>

<h3 style="font-size:1rem;font-weight:500;margin:32px 0 12px">5. Compartilhamento de Dados</h3>
<p>Seus dados podem ser compartilhados com: parceiros da plataforma (quando necessário para a prestação do serviço); prestadores de serviços essenciais (hospedagem, e-mail, analytics); autoridades competentes (quando exigido por lei).</p>
<p><strong>Não vendemos, alugamos ou comercializamos seus dados pessoais.</strong></p>

<h3 style="font-size:1rem;font-weight:500;margin:32px 0 12px">6. Cookies</h3>
<p>Utilizamos cookies para melhorar sua experiência na plataforma. Os cookies são classificados em: necessários (funcionamento básico); analytics (métricas de uso); marketing (comunicações personalizadas); preferências (idioma, moeda). Você pode gerenciar suas preferências de cookies a qualquer momento através do banner de consentimento.</p>

<h3 style="font-size:1rem;font-weight:500;margin:32px 0 12px">7. Segurança</h3>
<p>Adotamos medidas técnicas e organizacionais para proteger seus dados, incluindo: criptografia de senhas; conexões seguras (HTTPS); controle de acesso por perfil; logs de auditoria; backups regulares.</p>

<h3 style="font-size:1rem;font-weight:500;margin:32px 0 12px">8. Retenção de Dados</h3>
<p>Seus dados são mantidos pelo tempo necessário para cumprir as finalidades descritas nesta política, ou conforme exigido por lei. Dados de contas inativas podem ser excluídos após 24 meses de inatividade, mediante notificação prévia.</p>

<h3 style="font-size:1rem;font-weight:500;margin:32px 0 12px">9. Seus Direitos (LGPD)</h3>
<p>Você tem direito a: confirmar a existência de tratamento; acessar seus dados; corrigir dados incompletos ou desatualizados; solicitar anonimização ou eliminação de dados desnecessários; solicitar portabilidade; revogar consentimento; obter informações sobre compartilhamento.</p>

<h3 style="font-size:1rem;font-weight:500;margin:32px 0 12px">10. Contato do Encarregado (DPO)</h3>
<p>Para exercer seus direitos ou esclarecer dúvidas sobre esta política, entre em contato:</p>
<p>E-mail: <a href="mailto:privacidade@lexuscorretora.com.br" style="color:#B8945A">privacidade@lexuscorretora.com.br</a></p>

<h3 style="font-size:1rem;font-weight:500;margin:32px 0 12px">11. Alterações</h3>
<p>Esta política pode ser atualizada periodicamente. Recomendamos a consulta regular desta página. Alterações significativas serão comunicadas por e-mail ou notificação na plataforma.</p>
HTML;
    }

}
