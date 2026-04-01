<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Parceiro;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth};
use LEX\App\Services\Parceiros\ParceirosService;

final class PerfilController
{
    public function index(Requisicao $req): Resposta
    {
        $parceiro = ParceirosService::obterPorId(Auth::parceiroId());

        // Carregar documentos do parceiro
        $pdo = \LEX\Core\BancoDeDados::obter();
        $stmt = $pdo->prepare("SELECT * FROM parceiro_documentos WHERE parceiro_id = :pid ORDER BY created_at DESC");
        $stmt->execute(['pid' => Auth::parceiroId()]);
        $parceiro['documentos'] = $stmt->fetchAll();

        $conteudo = View::renderizar(__DIR__ . '/../../Views/parceiro/perfil.php', ['parceiro' => $parceiro]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'parceiro',
            'pageTitle' => I18n::t('sidebar_par.perfil'),
            'breadcrumbs' => [['label' => I18n::t('sidebar_par.perfil')]],
        ]));
    }

    public function salvar(Requisicao $req): Resposta
    {
        $parceiroId = Auth::parceiroId();
        $dados = $req->todosPost();
        unset($dados['_csrf_token']);

        // Converter arrays para JSON para campos que o service espera
        foreach (['specialties', 'service_states', 'service_cities'] as $campo) {
            if (isset($dados[$campo]) && is_array($dados[$campo])) {
                $dados[$campo] = array_values(array_filter($dados[$campo], fn($v) => trim($v) !== ''));
            }
        }

        ParceirosService::atualizar($parceiroId, $dados);

        // Processar upload de portfólio
        $portfolioFiles = $req->arquivo('portfolio');
        if ($portfolioFiles && !empty($portfolioFiles['tmp_name'])) {
            $this->processarUploadMultiplo($portfolioFiles, $parceiroId, 'portfolio');
        }

        // Processar upload de certidão de CNPJ
        $certidaoFile = $req->arquivo('certidao_cnpj');
        if ($certidaoFile && !empty($certidaoFile['tmp_name']) && $certidaoFile['error'] === UPLOAD_ERR_OK) {
            $this->salvarDocumento($certidaoFile, $parceiroId, 'certidao_cnpj');
        }

        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/parceiro/perfil');
    }

    private function processarUploadMultiplo(array $files, int $parceiroId, string $tipo): void
    {
        // Normalizar estrutura de $_FILES para múltiplos arquivos
        if (is_array($files['tmp_name'])) {
            for ($i = 0; $i < count($files['tmp_name']); $i++) {
                if (empty($files['tmp_name'][$i]) || $files['error'][$i] !== UPLOAD_ERR_OK) {
                    continue;
                }
                $arquivo = [
                    'name' => $files['name'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i],
                    'size' => $files['size'][$i],
                ];
                $this->salvarDocumento($arquivo, $parceiroId, $tipo);
            }
        } elseif (!empty($files['tmp_name']) && $files['error'] === UPLOAD_ERR_OK) {
            $this->salvarDocumento($files, $parceiroId, $tipo);
        }
    }

    private function salvarDocumento(array $arquivo, int $parceiroId, string $tipo): void
    {
        $ext = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
        $nomeUnico = sprintf('parceiro_%d_%s_%s.%s', $parceiroId, $tipo, bin2hex(random_bytes(8)), $ext);
        $diretorio = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '.', '/') . '/uploads/parceiros/' . date('Y/m');

        if (!is_dir($diretorio)) {
            mkdir($diretorio, 0755, true);
        }

        $destino = $diretorio . '/' . $nomeUnico;
        if (!move_uploaded_file($arquivo['tmp_name'], $destino)) {
            return;
        }

        $relativePath = 'uploads/parceiros/' . date('Y/m') . '/' . $nomeUnico;

        $pdo = \LEX\Core\BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "INSERT INTO parceiro_documentos (parceiro_id, type, name, file_path, file_size, mime_type)
             VALUES (:parceiro_id, :type, :name, :file_path, :file_size, :mime_type)"
        );
        $stmt->execute([
            'parceiro_id' => $parceiroId,
            'type' => $tipo,
            'name' => $arquivo['name'],
            'file_path' => $relativePath,
            'file_size' => $arquivo['size'],
            'mime_type' => $arquivo['type'] ?? '',
        ]);
    }
}

