<?php
/**
 * Template: Nova Oportunidade (Parceiro)
 * Variáveis: $nomeParceiro, $codigoDemanda, $tituloDemanda, $cidade, $estado, $siteUrl
 */
$primeiroNome = explode(' ', $nomeParceiro)[0];
?>
<p class="body-text">
Uma nova oportunidade compatível com seu perfil profissional está disponível para análise.
</p>

<p class="body-text">
Revise os detalhes do projeto e envie sua proposta o quanto antes para aumentar suas chances de seleção.
</p>

<div class="info-card">
<div class="info-card-title">— Dados da Oportunidade</div>
<div class="info-row">
<span class="info-key">Código</span>
<span class="info-val gold"><?= htmlspecialchars($codigoDemanda) ?></span>
</div>
<div class="info-row">
<span class="info-key">Título</span>
<span class="info-val"><?= htmlspecialchars($tituloDemanda) ?></span>
</div>
<div class="info-row">
<span class="info-key">Localização</span>
<span class="info-val"><?= htmlspecialchars($cidade) ?> / <?= htmlspecialchars($estado) ?></span>
</div>
</div>

<p class="body-text">
Acesse seu painel de parceiro para visualizar todos os detalhes e enviar sua proposta.
</p>

<div class="cta-wrapper">
<a href="<?= htmlspecialchars($siteUrl) ?>/parceiro/oportunidades" class="cta-btn">Ver Oportunidade</a>
</div>
