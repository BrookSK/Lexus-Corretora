<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Equipe;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, InicializadorSistema};

final class InicializacaoController
{
    public function index(Requisicao $req): Resposta
    {
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/inicializacao.php', ['resultados' => []]);
        $html = View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe', 'pageTitle' => 'Inicialização',
            'breadcrumbs' => [['label' => 'Inicialização']],
        ]);
        return Resposta::html($html);
    }

    public function executar(Requisicao $req): Resposta
    {
        $resultados = InicializadorSistema::executar();
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/inicializacao.php', ['resultados' => $resultados]);
        $html = View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe', 'pageTitle' => 'Inicialização',
            'breadcrumbs' => [['label' => 'Inicialização']],
        ]);
        return Resposta::html($html);
    }
}
