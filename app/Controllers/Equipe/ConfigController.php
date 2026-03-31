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

        // Processar uploads de imagem
        $uploadFields = ['logo', 'favicon', 'og_image'];
        foreach ($uploadFields as $field) {
            $arquivo = $req->arquivo($field);
            if ($arquivo && $arquivo['error'] === UPLOAD_ERR_OK && $arquivo['size'] > 0) {
                $path = self::processarUpload($arquivo, $field);
                if ($path) {
                    Settings::definir("sistema.{$field}", $path);
                }
            }
        }

        // Salvar campos de texto (ignorar campos vazios de senha/chave)
        $camposSensiveis = ['smtp.senha', 'stripe.test_secret_key', 'stripe.test_webhook_secret', 'stripe.live_secret_key', 'stripe.live_webhook_secret', 'asaas.sandbox_api_key', 'asaas.sandbox_webhook_token', 'asaas.production_api_key', 'asaas.production_webhook_token'];
        foreach ($dados as $chave => $valor) {
            if (in_array($chave, $uploadFields, true)) continue;
            // Não sobrescrever campos sensíveis se vieram vazios
            if (in_array($chave, $camposSensiveis, true) && $valor === '') continue;
            Settings::definir($chave, $valor);
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
        ];
        $keys = $map[$secao] ?? [];
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = Settings::obter($key, '');
        }
        return $result;
    }
}
