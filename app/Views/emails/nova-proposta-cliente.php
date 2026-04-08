<?php
/**
 * Template: Nova Proposta (Cliente)
 * Variáveis: $nomeCliente, $codigoDemanda, $nomeParceiro, $siteUrl
 */
$primeiroNome = explode(' ', $nomeCliente)[0];
?>
<p class="body-text">
Você recebeu uma nova proposta para sua demanda <strong><?= htmlspecialchars($codigoDemanda) ?></strong>.
</p>

<p class="body-text">
O parceiro <strong><?= htmlspecialchars($nomeParceiro) ?></strong> analisou seu projeto e enviou uma proposta detalhada para sua avaliação.
</p>

<div class="highlight-box">
<div class="highlight-box-label">— Próximos Passos</div>
<div class="highlight-box-text">
Acesse seu painel para revisar a proposta completa, incluindo valores, prazos e condições. Você pode aceitar, recusar ou solicitar ajustes.
</div>
</div>

<p class="body-text">
Recomendamos que você analise todas as propostas recebidas antes de tomar sua decisão.
</p>

<div class="cta-wrapper">
<a href="<?= htmlspecialchars($siteUrl) ?>/cliente/propostas" class="cta-btn">Ver Proposta</a>
</div>
