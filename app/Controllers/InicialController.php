<?php
declare(strict_types=1);
namespace LEX\App\Controllers;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n};

final class InicialController
{
    public function index(Requisicao $req): Resposta
    {
        $conteudo = View::renderizar(__DIR__ . '/../Views/institucional/home.php');
        $html = View::renderizar(__DIR__ . '/../Views/_layouts/public.php', [
            'conteudo'  => $conteudo,
            'pageTitle' => 'Lexus — ' . I18n::t('hero.label'),
        ]);
        return Resposta::html($html);
    }

    public function comoFunciona(Requisicao $req): Resposta
    {
        $conteudo = View::renderizar(__DIR__ . '/../Views/institucional/como-funciona.php');
        $html = View::renderizar(__DIR__ . '/../Views/_layouts/public.php', [
            'conteudo'  => $conteudo,
            'pageTitle' => I18n::t('pagina.como_funciona') . ' — Lexus',
        ]);
        return Resposta::html($html);
    }

    public function paraClientes(Requisicao $req): Resposta
    {
        $conteudo = View::renderizar(__DIR__ . '/../Views/institucional/para-clientes.php');
        $html = View::renderizar(__DIR__ . '/../Views/_layouts/public.php', [
            'conteudo'  => $conteudo,
            'pageTitle' => I18n::t('pagina.para_clientes') . ' — Lexus',
        ]);
        return Resposta::html($html);
    }

    public function paraParceiros(Requisicao $req): Resposta
    {
        $conteudo = View::renderizar(__DIR__ . '/../Views/institucional/para-parceiros.php');
        $html = View::renderizar(__DIR__ . '/../Views/_layouts/public.php', [
            'conteudo'  => $conteudo,
            'pageTitle' => I18n::t('pagina.para_parceiros') . ' — Lexus',
        ]);
        return Resposta::html($html);
    }

    public function vetriks(Requisicao $req): Resposta
    {
        $conteudo = View::renderizar(__DIR__ . '/../Views/institucional/vetriks.php');
        $html = View::renderizar(__DIR__ . '/../Views/_layouts/public.php', [
            'conteudo'  => $conteudo,
            'pageTitle' => I18n::t('pagina.vetriks') . ' — Lexus',
        ]);
        return Resposta::html($html);
    }

    public function sobre(Requisicao $req): Resposta
    {
        $conteudo = View::renderizar(__DIR__ . '/../Views/institucional/sobre.php');
        $html = View::renderizar(__DIR__ . '/../Views/_layouts/public.php', [
            'conteudo'  => $conteudo,
            'pageTitle' => I18n::t('pagina.sobre') . ' — Lexus',
        ]);
        return Resposta::html($html);
    }

    public function abrirDemanda(Requisicao $req): Resposta
    {
        $conteudo = View::renderizar(__DIR__ . '/../Views/institucional/abrir-demanda.php');
        $html = View::renderizar(__DIR__ . '/../Views/_layouts/public.php', [
            'conteudo'  => $conteudo,
            'pageTitle' => I18n::t('demanda.titulo') . ' — Lexus',
        ]);
        return Resposta::html($html);
    }

    public function salvarDemanda(Requisicao $req): Resposta
    {
        // TODO: Validar e salvar demanda pública
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('demanda.sucesso')];
        return Resposta::redirecionar('/abrir-demanda');
    }

    public function sejaParceiro(Requisicao $req): Resposta
    {
        $conteudo = View::renderizar(__DIR__ . '/../Views/institucional/seja-parceiro.php');
        $html = View::renderizar(__DIR__ . '/../Views/_layouts/public.php', [
            'conteudo'  => $conteudo,
            'pageTitle' => I18n::t('parceiro.titulo') . ' — Lexus',
        ]);
        return Resposta::html($html);
    }

    public function salvarParceiro(Requisicao $req): Resposta
    {
        // TODO: Validar e salvar cadastro de parceiro
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('parceiro.sucesso')];
        return Resposta::redirecionar('/seja-parceiro');
    }

    public function trocarIdioma(Requisicao $req): Resposta
    {
        $idioma = $req->post('idioma', 'pt-BR');
        I18n::definirIdioma($idioma);
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        return Resposta::redirecionar($referer);
    }

    public function trocarMoeda(Requisicao $req): Resposta
    {
        $moeda = $req->post('moeda', 'BRL');
        I18n::definirMoeda($moeda);
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        return Resposta::redirecionar($referer);
    }
}
