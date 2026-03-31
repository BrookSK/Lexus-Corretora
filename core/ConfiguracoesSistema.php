<?php
declare(strict_types=1);
namespace LEX\Core;

final class ConfiguracoesSistema
{
    public static function billingAtivo(): bool
    {
        return (bool)Settings::obter('billing.ativo', false);
    }

    public static function stripeMode(): string
    {
        return Settings::obter('stripe.mode', 'sandbox');
    }

    public static function stripeSecretKey(): string
    {
        $mode = self::stripeMode();
        return $mode === 'production'
            ? Settings::obter('stripe.live_secret_key', '')
            : Settings::obter('stripe.test_secret_key', '');
    }

    public static function stripePublishableKey(): string
    {
        $mode = self::stripeMode();
        return $mode === 'production'
            ? Settings::obter('stripe.live_publishable_key', '')
            : Settings::obter('stripe.test_publishable_key', '');
    }

    public static function stripeWebhookSecret(): string
    {
        $mode = self::stripeMode();
        return $mode === 'production'
            ? Settings::obter('stripe.live_webhook_secret', '')
            : Settings::obter('stripe.test_webhook_secret', '');
    }

    public static function asaasMode(): string
    {
        return Settings::obter('asaas.mode', 'sandbox');
    }

    public static function asaasApiKey(): string
    {
        $mode = self::asaasMode();
        return $mode === 'production'
            ? Settings::obter('asaas.production_api_key', '')
            : Settings::obter('asaas.sandbox_api_key', '');
    }

    public static function asaasWebhookToken(): string
    {
        $mode = self::asaasMode();
        return $mode === 'production'
            ? Settings::obter('asaas.production_webhook_token', '')
            : Settings::obter('asaas.sandbox_webhook_token', '');
    }

    public static function smtpConfig(): array
    {
        return [
            'host'     => Settings::obter('smtp.host', Configuracao::obter('smtp.host', '')),
            'porta'    => (int)Settings::obter('smtp.porta', Configuracao::obter('smtp.porta', 587)),
            'usuario'  => Settings::obter('smtp.usuario', Configuracao::obter('smtp.usuario', '')),
            'senha'    => Settings::obter('smtp.senha', Configuracao::obter('smtp.senha', '')),
            'de_email' => Settings::obter('smtp.de_email', Configuracao::obter('smtp.de_email', '')),
            'de_nome'  => Settings::obter('smtp.de_nome', Configuracao::obter('smtp.de_nome', 'Lexus Corretora')),
        ];
    }

    public static function uploadMaxSize(): int
    {
        return (int)Settings::obter('upload.max_size_mb', 20) * 1024 * 1024;
    }

    public static function uploadAllowedTypes(): array
    {
        $tipos = Settings::obter('upload.allowed_types', 'pdf,jpg,jpeg,png,gif,doc,docx,xls,xlsx,dwg,dxf,zip');
        return is_array($tipos) ? $tipos : explode(',', $tipos);
    }
}
