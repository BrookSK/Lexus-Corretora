<?php
/**
 * Template: Proposta Recusada (Parceiro)
 * Variáveis: $nomeParceiro, $codigoDemanda, $siteUrl
 */
$primeiroNome = explode(' ', $nomeParceiro)[0];
?>
<p class="body-text">
Informamos que sua proposta para a demanda <strong><?= htmlspecialchars($codigoDemanda) ?></strong> não foi selecionada desta vez.
</p>

<p class="body-text">
Sabemos que cada projeto é uma oportunidade de crescimento. Continue acompanhando novas oportunidades em nossa plataforma.
</p>

<div class="highlight-box">
<div class="highlight-box-label">— Continue Crescendo</div>
<div class="highlight-box-text">
Novas oportunidades surgem diariamente. Mantenha seu perfil atualizado e continue enviando propostas de qualidade para aumentar suas chances de seleção.
</div>
</div>

<p class="body-text">
Estamos aqui para apoiar seu crescimento profissional.
</p>

<div class="cta-wrapper">
<a href="<?= htmlspecialchars($siteUrl) ?>/parceiro/oportunidades" class="cta-btn">Ver Novas Oportunidades</a>
</div>
