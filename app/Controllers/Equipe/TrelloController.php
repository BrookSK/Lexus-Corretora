<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Equipe;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Settings, Auth};

final class TrelloController
{
    /**
     * Callback após autorização do Trello (o token vem via fragment #token=xxx,
     * então usamos JS para capturar e enviar ao servidor)
     */
    public function callback(Requisicao $req): Resposta
    {
        $token = $req->get('token', '');
        if (!empty($token)) {
            Settings::definir('trello.api_token', $token);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Trello conectado com sucesso!'];
            return Resposta::redirecionar('/equipe/configuracoes/trello');
        }

        // O Trello retorna o token no fragment (#token=xxx), não na query string
        // Precisamos de uma página intermediária com JS para capturar
        $html = <<<'HTML'
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Conectando Trello...</title>
<style>body{font-family:'Outfit',sans-serif;background:#0C0C0A;color:#F5F2ED;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0}
.box{text-align:center}.spin{width:40px;height:40px;border:3px solid rgba(184,148,90,.2);border-top-color:#B8945A;border-radius:50%;animation:spin 1s linear infinite;margin:0 auto 20px}
@keyframes spin{to{transform:rotate(360deg)}}p{color:rgba(245,242,237,.5);font-size:.9rem}</style></head>
<body><div class="box"><div class="spin"></div><p>Conectando ao Trello...</p></div>
<script>
var hash=window.location.hash;
if(hash){
  var token=hash.replace('#token=','');
  window.location.href='/equipe/trello/callback?token='+encodeURIComponent(token);
}else{
  document.querySelector('p').textContent='Erro: token não recebido. Tente novamente.';
}
</script></body></html>
HTML;
        return Resposta::html($html);
    }

    /**
     * Retorna boards e listas do usuário (AJAX)
     */
    public function boards(Requisicao $req): Resposta
    {
        $apiKey = Settings::obter('trello.api_key', '');
        $token = Settings::obter('trello.api_token', '');

        if (empty($apiKey) || empty($token)) {
            return Resposta::json(['erro' => 'Trello não configurado'], 400);
        }

        $url = "https://api.trello.com/1/members/me/boards?key={$apiKey}&token={$token}&fields=name,url&lists=open";
        $ch = curl_init($url);
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 10]);
        $resp = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($code !== 200) {
            return Resposta::json(['erro' => 'Falha ao buscar boards'], 500);
        }

        $boards = json_decode($resp, true) ?: [];
        $result = [];
        foreach ($boards as $board) {
            $b = ['id' => $board['id'], 'name' => $board['name'], 'lists' => []];
            // Buscar listas do board
            $listsUrl = "https://api.trello.com/1/boards/{$board['id']}/lists?key={$apiKey}&token={$token}&fields=name";
            $ch2 = curl_init($listsUrl);
            curl_setopt_array($ch2, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 10]);
            $listsResp = curl_exec($ch2);
            curl_close($ch2);
            $lists = json_decode($listsResp, true) ?: [];
            foreach ($lists as $list) {
                $b['lists'][] = ['id' => $list['id'], 'name' => $list['name']];
            }
            $result[] = $b;
        }

        return Resposta::json($result);
    }

    /**
     * Salva a lista selecionada
     */
    public function salvarLista(Requisicao $req): Resposta
    {
        $listId = $req->post('list_id', '');
        $listContato = $req->post('list_contato', '');
        $listDemanda = $req->post('list_demanda', '');
        $listParceiro = $req->post('list_parceiro', '');

        if (!empty($listId)) Settings::definir('trello.list_id', $listId);
        if (!empty($listContato)) Settings::definir('trello.list_contato', $listContato);
        if (!empty($listDemanda)) Settings::definir('trello.list_demanda', $listDemanda);
        if (!empty($listParceiro)) Settings::definir('trello.list_parceiro', $listParceiro);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Listas do Trello salvas!'];
        return Resposta::redirecionar('/equipe/configuracoes/trello');
    }

    /**
     * Desconecta o Trello
     */
    public function desconectar(Requisicao $req): Resposta
    {
        Settings::remover('trello.api_token');
        Settings::remover('trello.list_id');
        Settings::remover('trello.list_contato');
        Settings::remover('trello.list_demanda');
        Settings::remover('trello.list_parceiro');
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Trello desconectado.'];
        return Resposta::redirecionar('/equipe/configuracoes/trello');
    }
}
