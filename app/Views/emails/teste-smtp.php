<?php
/**
 * Template: Teste SMTP
 * Variáveis: $siteUrl
 */
$dataHora = date('d/m/Y H:i:s');
?>
<p class="body-text">
Este é um e-mail de teste para verificar as configurações SMTP do sistema.
</p>

<p class="body-text">
Se você está recebendo esta mensagem, significa que as configurações estão funcionando corretamente!
</p>

<div class="info-card">
<div class="info-card-title">— Informações do Teste</div>
<div class="info-row">
<span class="info-key">Data/Hora</span>
<span class="info-val"><?= htmlspecialchars($dataHora) ?></span>
</div>
<div class="info-row">
<span class="info-key">Status</span>
<span class="info-val gold">Enviado com Sucesso</span>
</div>
</div>

<p class="body-text">
Todas as funcionalidades de envio de e-mail estão operacionais.
</p>
