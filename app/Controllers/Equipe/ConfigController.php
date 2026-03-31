<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Equipe;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Settings, Auth};
use LEX\App\Services\Audit\AuditService;

final class ConfigController
{
    public function index(Requisicao $req): Resposta
    {
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/configuracoes.php', []);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => I18n::t('sidebar.configuracoes'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.configuracoes')]],
        ]));
    }

    public function salvar(Requisicao $req): Resposta
    {
        $dados = $req->todosPost();
        unset($dados['_csrf_token']);
        foreach ($dados as $chave => $valor) {
            Settings::definir($chave, $valor);
        }
        AuditService::registrar('equipe', Auth::equipeId(), 'config.salvar', 'settings', null);
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/equipe/configuracoes');
    }

    public function secao(Requisicao $req): Resposta
    {
        $secao = $req->param('secao', 'branding');
        $settings = self::obterSettingsSecao($secao);
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/configuracoes-secao.php', [
            'secao' => $secao, 'settings' => $settings,
        ]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => I18n::t('sidebar.configuracoes'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.configuracoes'), 'url' => '/equipe/configuracoes'], ['label' => ucfirst($secao)]],
        ]));
    }

    public function salvarSecao(Requisicao $req): Resposta
    {
        $secao = $req->param('secao', 'branding');
        $dados = $req->todosPost();
        unset($dados['_csrf_token']);

        // Processar uploads de imagem — mapear campo para chave de settings correta
        $uploadMap = [
            'logo'     => 'sistema.logo',
            'favicon'  => 'sistema.favicon',
            'og_image' => 'seo.og_image',
        ];
        foreach ($uploadMap as $field => $settingKey) {
            $arquivo = $req->arquivo($field);
            if ($arquivo && $arquivo['error'] === UPLOAD_ERR_OK && $arquivo['size'] > 0) {
                $path = self::processarUpload($arquivo, $field);
                if ($path) {
                    Settings::definir($settingKey, $path);
                }
            }
        }

        // Campos de upload não devem ser processados como texto
        $uploadFields = array_keys($uploadMap);

        // Salvar campos de texto (ignorar campos vazios de senha/chave)
        $camposSensiveis = ['smtp.senha', 'stripe.test_secret_key', 'stripe.test_webhook_secret', 'stripe.live_secret_key', 'stripe.live_webhook_secret', 'asaas.sandbox_api_key', 'asaas.sandbox_webhook_token', 'asaas.production_api_key', 'asaas.production_webhook_token'];
        foreach ($dados as $chave => $valor) {
            if (in_array($chave, $uploadFields, true)) continue;
            // PHP converte pontos em underscores em nomes de campos multipart — reverter
            $chaveReal = str_replace('_', '.', $chave);
            // Verificar se a chave original com pontos faz mais sentido
            // Ex: seo_ga_id -> seo.ga.id (errado) vs seo.ga_id (certo)
            // Usar mapeamento explícito para evitar ambiguidade
            $chaveReal = self::mapearChave($chave);
            // Não sobrescrever campos sensíveis se vieram vazios
            if (in_array($chaveReal, $camposSensiveis, true) && $valor === '') continue;
            Settings::definir($chaveReal, $valor);
        }

        AuditService::registrar('equipe', Auth::equipeId(), 'config.secao.' . $secao, 'settings', null);
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/equipe/configuracoes/' . $secao);
    }

    private static function processarUpload(array $arquivo, string $tipo): ?string
    {
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp', 'image/x-icon'];
        $mime = $arquivo['type'] ?? '';
        if (!in_array($mime, $allowedMimes, true)) return null;
        if ($arquivo['size'] > 5 * 1024 * 1024) return null; // max 5MB

        $ext = match($mime) {
            'image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif',
            'image/svg+xml' => 'svg', 'image/webp' => 'webp', 'image/x-icon' => 'ico',
            default => 'png',
        };
        $nomeArquivo = $tipo . '_' . time() . '.' . $ext;
        $destDir = dirname(__DIR__, 3) . '/public/assets/uploads/config';
        if (!is_dir($destDir)) mkdir($destDir, 0755, true);
        $destino = $destDir . '/' . $nomeArquivo;

        if (move_uploaded_file($arquivo['tmp_name'], $destino)) {
            return '/assets/uploads/config/' . $nomeArquivo;
        }
        return null;
    }

    private static function mapearChave(string $chavePost): string
    {
        // Mapeamento explícito: PHP converte . em _ nos nomes de campos POST
        // Precisamos saber quais underscores eram pontos originalmente
        static $mapa = [
            'sistema_nome' => 'sistema.nome', 'sistema_slogan' => 'sistema.slogan',
            'sistema_cor_primaria' => 'sistema.cor_primaria', 'sistema_copyright' => 'sistema.copyright',
            'sistema_idioma_padrao' => 'sistema.idioma_padrao', 'sistema_moeda_padrao' => 'sistema.moeda_padrao',
            'sistema_timezone' => 'sistema.timezone',
            'smtp_host' => 'smtp.host', 'smtp_porta' => 'smtp.porta',
            'smtp_usuario' => 'smtp.usuario', 'smtp_senha' => 'smtp.senha',
            'smtp_de_email' => 'smtp.de_email', 'smtp_de_nome' => 'smtp.de_nome',
            'seo_meta_title' => 'seo.meta_title', 'seo_meta_description' => 'seo.meta_description',
            'seo_og_image' => 'seo.og_image', 'seo_ga_id' => 'seo.ga_id', 'seo_indexacao' => 'seo.indexacao',
            'stripe_mode' => 'stripe.mode',
            'stripe_test_publishable_key' => 'stripe.test_publishable_key',
            'stripe_test_secret_key' => 'stripe.test_secret_key',
            'stripe_test_webhook_secret' => 'stripe.test_webhook_secret',
            'stripe_live_publishable_key' => 'stripe.live_publishable_key',
            'stripe_live_secret_key' => 'stripe.live_secret_key',
            'stripe_live_webhook_secret' => 'stripe.live_webhook_secret',
            'asaas_mode' => 'asaas.mode',
            'asaas_sandbox_api_key' => 'asaas.sandbox_api_key',
            'asaas_sandbox_webhook_token' => 'asaas.sandbox_webhook_token',
            'asaas_production_api_key' => 'asaas.production_api_key',
            'asaas_production_webhook_token' => 'asaas.production_webhook_token',
            'legal_termos' => 'legal.termos', 'legal_privacidade' => 'legal.privacidade',
            'notificacoes_email_ativo' => 'notificacoes.email_ativo',
            'notificacoes_painel_ativo' => 'notificacoes.painel_ativo',
            'seguranca_2fa_obrigatorio' => 'seguranca.2fa_obrigatorio',
            'seguranca_tentativas_login' => 'seguranca.tentativas_login',
            'seguranca_bloqueio_minutos' => 'seguranca.bloqueio_minutos',
            'trello_api_key' => 'trello.api_key', 'trello_api_token' => 'trello.api_token',
            'trello_list_id' => 'trello.list_id', 'trello_list_contato' => 'trello.list_contato',
            'trello_list_demanda' => 'trello.list_demanda', 'trello_list_parceiro' => 'trello.list_parceiro',
        ];
        return $mapa[$chavePost] ?? $chavePost;
    }

    private static function obterSettingsSecao(string $secao): array
    {
        $map = [
            'branding' => ['sistema.nome', 'sistema.slogan', 'sistema.logo', 'sistema.favicon', 'sistema.cor_primaria', 'sistema.copyright'],
            'smtp' => ['smtp.host', 'smtp.porta', 'smtp.usuario', 'smtp.senha', 'smtp.de_email', 'smtp.de_nome'],
            'seo' => ['seo.meta_title', 'seo.meta_description', 'seo.og_image', 'seo.ga_id', 'seo.indexacao'],
            'cobranca' => ['stripe.mode', 'stripe.test_publishable_key', 'stripe.test_secret_key', 'stripe.test_webhook_secret', 'stripe.live_publishable_key', 'stripe.live_secret_key', 'stripe.live_webhook_secret', 'asaas.mode', 'asaas.sandbox_api_key', 'asaas.sandbox_webhook_token', 'asaas.production_api_key', 'asaas.production_webhook_token'],
            'notificacoes' => ['notificacoes.email_ativo', 'notificacoes.painel_ativo'],
            'seguranca' => ['seguranca.2fa_obrigatorio', 'seguranca.tentativas_login', 'seguranca.bloqueio_minutos'],
            'geral' => ['sistema.idioma_padrao', 'sistema.moeda_padrao', 'sistema.timezone'],
            'legal' => ['legal.termos', 'legal.privacidade'],
            'trello' => ['trello.api_key', 'trello.api_token', 'trello.list_id', 'trello.list_contato', 'trello.list_demanda', 'trello.list_parceiro'],
        ];
        $keys = $map[$secao] ?? [];
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = Settings::obter($key, '');
        }
        return $result;
    }
}
