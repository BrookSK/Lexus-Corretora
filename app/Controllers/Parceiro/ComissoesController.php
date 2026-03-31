<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Parceiro;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth};
use LEX\App\Services\Comissoes\ComissoesService;

final class ComissoesController
{
    public function index(Requisicao $req): Resposta
    {
        $comissoes = ComissoesService::listarPorParceiro(Auth::parceiroId());
        $conteudo = View::renderizar(__DIR__ . '/../../Views/parceiro/comissoes.php', ['comissoes' => $comissoes]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'parceiro',
            'pageTitle' => I18n::t('sidebar_par.comissoes'),
            'breadcrumbs' => [['label' => I18n::t('sidebar_par.comissoes')]],
        ]));
    }
}
