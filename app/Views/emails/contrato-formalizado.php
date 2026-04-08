<?php
/**
 * Template: Contrato Formalizado
 * Variáveis: $nomeDestinatario, $codigoDemanda, $valor, $siteUrl
 */
$primeiroNome = explode(' ', $nomeDestinatario)[0];
?>
<p class="body-text">
O contrato referente à demanda <strong><?= htmlspecialchars($codigoDemanda) ?></strong> foi oficialmente formalizado.
</p>

<p class="body-text">
Todos os termos e condições foram acordados e o projeto está pronto para iniciar.
</p>

<div class="info-card">
<div class="info-card-title">— Dados do Contrato</div>
<div class="info-row">
<span class="info-key">Código da Demanda</span>
<span class="info-val gold"><?= htmlspecialchars($codigoDemanda) ?></span>
</div>
<div class="info-row">
<span class="info-key">Valor do Contrato</span>
<span class="info-val"><?= htmlspecialchars($valor) ?></span>
</div>
</div>

<p class="body-text">
Você pode acessar o contrato completo e acompanhar o andamento do projeto através do seu painel.
</p>

<div class="cta-wrapper">
<a href="<?= htmlspecialchars($siteUrl) ?>/equipe/contratos" class="cta-btn">Ver Contrato</a>
</div>
