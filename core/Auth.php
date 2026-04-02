<?php
declare(strict_types=1);
namespace LEX\Core;

final class Auth
{
    // --- Equipe ---
    public static function loginEquipe(array $usuario): void
    {
        session_regenerate_id(true);
        $_SESSION['equipe_id']     = $usuario['id'];
        $_SESSION['equipe_nome']   = $usuario['name'];
        $_SESSION['equipe_email']  = $usuario['email'];
        $_SESSION['equipe_role']   = $usuario['role_slug'] ?? 'admin';
        $_SESSION['equipe_avatar'] = $usuario['avatar'] ?? null;
    }

    public static function logoutEquipe(): void
    {
        unset($_SESSION['equipe_id'], $_SESSION['equipe_nome'], $_SESSION['equipe_email'], $_SESSION['equipe_role'], $_SESSION['equipe_avatar']);
        unset($_SESSION['impersonando_cliente'], $_SESSION['impersonando_parceiro']);
    }

    public static function equipeLogada(): bool { return !empty($_SESSION['equipe_id']); }
    public static function equipeId(): ?int { return isset($_SESSION['equipe_id']) ? (int)$_SESSION['equipe_id'] : null; }
    public static function equipeNome(): ?string { return $_SESSION['equipe_nome'] ?? null; }
    public static function equipeEmail(): ?string { return $_SESSION['equipe_email'] ?? null; }
    public static function equipeRole(): ?string { return $_SESSION['equipe_role'] ?? null; }
    public static function equipeAvatar(): ?string { return $_SESSION['equipe_avatar'] ?? null; }

    // --- Cliente ---
    public static function loginCliente(array $cliente): void
    {
        session_regenerate_id(true);
        $_SESSION['cliente_id']     = $cliente['id'];
        $_SESSION['cliente_nome']   = $cliente['name'];
        $_SESSION['cliente_email']  = $cliente['email'];
        $_SESSION['cliente_avatar'] = $cliente['avatar'] ?? null;
    }

    public static function logoutCliente(): void
    {
        unset($_SESSION['cliente_id'], $_SESSION['cliente_nome'], $_SESSION['cliente_email'], $_SESSION['cliente_avatar']);
    }

    public static function clienteLogado(): bool { return !empty($_SESSION['cliente_id']); }
    public static function clienteId(): ?int { return isset($_SESSION['cliente_id']) ? (int)$_SESSION['cliente_id'] : null; }
    public static function clienteNome(): ?string { return $_SESSION['cliente_nome'] ?? null; }
    public static function clienteEmail(): ?string { return $_SESSION['cliente_email'] ?? null; }
    public static function clienteAvatar(): ?string { return $_SESSION['cliente_avatar'] ?? null; }

    // --- Parceiro ---
    public static function loginParceiro(array $parceiro): void
    {
        session_regenerate_id(true);
        $_SESSION['parceiro_id']     = $parceiro['id'];
        $_SESSION['parceiro_nome']   = $parceiro['name'];
        $_SESSION['parceiro_email']  = $parceiro['email'];
        $_SESSION['parceiro_avatar'] = $parceiro['avatar'] ?? null;
    }

    public static function logoutParceiro(): void
    {
        unset($_SESSION['parceiro_id'], $_SESSION['parceiro_nome'], $_SESSION['parceiro_email'], $_SESSION['parceiro_avatar']);
    }

    public static function parceiroLogado(): bool { return !empty($_SESSION['parceiro_id']); }
    public static function parceiroId(): ?int { return isset($_SESSION['parceiro_id']) ? (int)$_SESSION['parceiro_id'] : null; }
    public static function parceiroNome(): ?string { return $_SESSION['parceiro_nome'] ?? null; }
    public static function parceiroEmail(): ?string { return $_SESSION['parceiro_email'] ?? null; }
    public static function parceiroAvatar(): ?string { return $_SESSION['parceiro_avatar'] ?? null; }

    // --- Impersonação ---
    public static function impersonarCliente(int $clienteId): void
    {
        $_SESSION['impersonando_cliente'] = $clienteId;
    }

    public static function impersonarParceiro(int $parceiroId): void
    {
        $_SESSION['impersonando_parceiro'] = $parceiroId;
    }

    public static function pararImpersonacao(): void
    {
        unset($_SESSION['impersonando_cliente'], $_SESSION['impersonando_parceiro']);
    }

    public static function estaImpersonando(): bool
    {
        return !empty($_SESSION['impersonando_cliente']) || !empty($_SESSION['impersonando_parceiro']);
    }
}
