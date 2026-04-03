<?php
declare(strict_types=1);
namespace LEX\App\Controllers;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth, BancoDeDados};
use LEX\App\Services\Integracoes\TrelloService;
use LEX\App\Services\Demandas\DemandasService;
use LEX\App\Services\Timeline\TimelineService;
use LEX\App\Services\Arquivos\ArquivosService;

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
        $html = View::renderizar(__DIR__ . '/../Views/_layouts/landing.php', [
            'conteudo'  => $conteudo,
            'pageTitle' => 'Para Clientes — Lexus',
        ]);
        return Resposta::html($html);
    }

    public function paraParceiros(Requisicao $req): Resposta
    {
        $conteudo = View::renderizar(__DIR__ . '/../Views/institucional/para-parceiros.php');
        $html = View::renderizar(__DIR__ . '/../Views/_layouts/landing.php', [
            'conteudo'  => $conteudo,
            'pageTitle' => 'Para Parceiros — Lexus',
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
        $dados = $req->todosPost();
        unset($dados['_csrf_token']);

        $nome   = trim($dados['name'] ?? '');
        $email  = trim($dados['email'] ?? '');
        $senha  = $dados['password'] ?? '';
        $titulo = trim($dados['title'] ?? '');

        // Log para debug
        \LEX\Core\AppLogger::info('salvarDemanda recebido', [
            'nome' => $nome, 'email' => $email, 'titulo' => $titulo,
            'senha_len' => strlen($senha), 'ip' => $req->ip(),
        ]);

        if (empty($nome) || empty($email) || strlen($senha) < 8 || empty($titulo)) {
            \LEX\Core\AppLogger::info('salvarDemanda validacao falhou', ['nome'=>$nome,'email'=>$email,'titulo'=>$titulo,'senha_len'=>strlen($senha)]);
            $_SESSION['flash'] = ['type' => 'error', 'message' => I18n::t('erro.validacao')];
            $voltar = str_contains($_SERVER['HTTP_REFERER'] ?? '', 'para-clientes') ? '/para-clientes' : '/abrir-demanda';
            return Resposta::redirecionar($voltar);
        }

        $pdo = BancoDeDados::obter();

        $exists = $pdo->prepare("SELECT id FROM clientes WHERE email = :e LIMIT 1");
        $exists->execute(['e' => $email]);
        if ($exists->fetch()) {
            \LEX\Core\AppLogger::info('salvarDemanda email ja existe', ['email'=>$email]);
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'E-mail já cadastrado. Acesse sua conta para abrir uma demanda.'];
            $voltar = str_contains($_SERVER['HTTP_REFERER'] ?? '', 'para-clientes') ? '/para-clientes' : '/abrir-demanda';
            return Resposta::redirecionar($voltar);
        }

        $hash = password_hash($senha, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $pdo->prepare("INSERT INTO clientes (name, email, password, phone, company, city, state, is_active, created_at) VALUES (:n, :e, :p, :phone, :company, :city, :state, 1, NOW())");
        $stmt->execute([
            'n'       => $nome,
            'e'       => $email,
            'p'       => $hash,
            'phone'   => $dados['phone'] ?? null,
            'company' => $dados['company'] ?? null,
            'city'    => $dados['city'] ?? null,
            'state'   => $dados['state'] ?? null,
        ]);
        $clienteId = (int)$pdo->lastInsertId();
        \LEX\Core\AppLogger::info('salvarDemanda cliente criado', ['clienteId'=>$clienteId]);

        // Limpar máscara BRL dos campos monetários
        foreach (['budget_min', 'budget_max'] as $campo) {
            if (!empty($dados[$campo])) {
                $dados[$campo] = preg_replace('/[^\d,.]/', '', $dados[$campo]);
                $dados[$campo] = str_replace(',', '.', str_replace('.', '', $dados[$campo]));
                if (!is_numeric($dados[$campo])) $dados[$campo] = null;
            }
        }

        $dadosDemanda = array_intersect_key($dados, array_flip([
            'title','description','category','work_type','urgency','address',
            'area_sqm','desired_deadline','budget_min','budget_max','notes',
            'city','state',
        ]));
        $dadosDemanda['cliente_id'] = $clienteId;
        $dadosDemanda['origin']     = 'lead';
        $dadosDemanda['status']     = 'novo';
        $demandaId = DemandasService::criar($dadosDemanda);
        \LEX\Core\AppLogger::info('salvarDemanda demanda criada', ['demandaId'=>$demandaId]);
        TimelineService::registrar($demandaId, 'demanda_criada', 'Demanda criada via formulário público', 'cliente', $clienteId);

        // Processar arquivos enviados
        $filesRaw = $_FILES['files'] ?? [];
        if (!empty($filesRaw['name'])) {
            foreach ($filesRaw['name'] as $i => $nome) {
                $arq = ['name' => $nome, 'type' => $filesRaw['type'][$i], 'tmp_name' => $filesRaw['tmp_name'][$i], 'error' => $filesRaw['error'][$i], 'size' => $filesRaw['size'][$i]];
                if ($arq['error'] === UPLOAD_ERR_OK) {
                    try { ArquivosService::upload($arq, 'demanda', $demandaId); } catch (\Throwable $e) { /* silenciar */ }
                }
            }
        }

        // Integração Trello
        try { TrelloService::cardDemanda($dados); } catch (\Throwable $e) { /* silenciar */ }

        // E-mail de confirmação para o cliente
        try {
            $demanda = \LEX\App\Services\Demandas\DemandasService::obterPorId($demandaId);
            if ($demanda) {
                $emailOk = \LEX\App\Services\Email\EmailService::novaDemanda($email, $nome, $demanda['code'] ?? '', $demanda['title'] ?? '');
                \LEX\Core\AppLogger::info('salvarDemanda email enviado', ['para'=>$email,'ok'=>$emailOk]);
            }
        } catch (\Throwable $e) {
            \LEX\Core\AppLogger::erro('salvarDemanda email erro: ' . $e->getMessage());
        }

        Auth::loginCliente(['id' => $clienteId, 'name' => $nome, 'email' => $email]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('demanda.sucesso')];
        return Resposta::redirecionar('/cliente/dashboard');
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
        $dados = $req->todosPost();
        unset($dados['_csrf_token']);

        $nome  = trim($dados['name'] ?? '');
        $email = trim($dados['email'] ?? '');
        $senha = $dados['password'] ?? '';
        $tipo  = $dados['type'] ?? 'prestador';

        if (empty($nome) || empty($email) || strlen($senha) < 8) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => I18n::t('erro.validacao')];
            return Resposta::redirecionar('/seja-parceiro');
        }

        $pdo = BancoDeDados::obter();

        $exists = $pdo->prepare("SELECT id FROM parceiros WHERE email = :e LIMIT 1");
        $exists->execute(['e' => $email]);
        if ($exists->fetch()) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'E-mail já cadastrado. Acesse sua conta de parceiro.'];
            return Resposta::redirecionar('/seja-parceiro');
        }

        $hash = password_hash($senha, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $pdo->prepare("INSERT INTO parceiros (name, email, password, phone, whatsapp, document, type, bio, specialties, status, is_active, created_at) VALUES (:n, :e, :p, :phone, :whatsapp, :document, :type, :bio, :specialties, 'pendente_analise', 1, NOW())");
        $stmt->execute([
            'n'           => $nome,
            'e'           => $email,
            'p'           => $hash,
            'phone'       => $dados['whatsapp'] ?? null,
            'whatsapp'    => $dados['whatsapp'] ?? null,
            'document'    => $dados['document'] ?? null,
            'type'        => $tipo,
            'bio'         => $dados['description'] ?? null,
            'specialties' => !empty($dados['specialties']) ? json_encode((array)$dados['specialties']) : null,
        ]);
        $parceiroId = (int)$pdo->lastInsertId();

        // Processar portfólio (múltiplos arquivos)
        $portfolio = $_FILES['portfolio'] ?? [];
        if (!empty($portfolio['name'])) {
            foreach ($portfolio['name'] as $i => $nome) {
                $arq = ['name' => $nome, 'type' => $portfolio['type'][$i], 'tmp_name' => $portfolio['tmp_name'][$i], 'error' => $portfolio['error'][$i], 'size' => $portfolio['size'][$i]];
                if ($arq['error'] === UPLOAD_ERR_OK) {
                    try { ArquivosService::upload($arq, 'parceiro', $parceiroId, 'portfolio'); } catch (\Throwable $e) { /* silenciar */ }
                }
            }
        }

        // Processar certidão CNPJ (arquivo único)
        $certidao = $_FILES['certidao_cnpj'] ?? [];
        if (!empty($certidao['tmp_name']) && ($certidao['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
            try { ArquivosService::upload($certidao, 'parceiro', $parceiroId, 'certidao_cnpj'); } catch (\Throwable $e) { /* silenciar */ }
        }

        // Integração Trello
        try { TrelloService::cardParceiro($dados); } catch (\Throwable $e) { /* silenciar */ }

        // E-mail de boas-vindas
        try { \LEX\App\Services\Email\EmailService::boasVindasParceiro($email, $nome); } catch (\Throwable $e) { /* silenciar */ }

        Auth::loginParceiro(['id' => $parceiroId, 'name' => $nome, 'email' => $email]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('parceiro.sucesso')];
        return Resposta::redirecionar('/parceiro/dashboard');
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
