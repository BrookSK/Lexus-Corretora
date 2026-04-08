<?php
/**
 * Template: Proposta Selecionada (Parceiro)
 * Variáveis: $nomeParceiro, $codigoDemanda, $siteUrl
 */
$primeiroNome = explode(' ', $nomeParceiro)[0];
?>
<p class="body-text">
Parabéns! Sua proposta para a demanda <strong><?= htmlspecialchars($codigoDemanda) ?></strong> foi selecionada pelo cliente.
</p>

<p class="body-text">
Este é um momento importante. Nossa equipe entrará em contato em breve para formalizar o contrato e alinhar os próximos passos do projeto.
</p>

<div class="highlight-box">
<div class="highlight-box-label">— Próximos Passos</div>
<div class="highlight-box-text">
Aguarde o contato de nossa equipe para formalização do contrato. Mantenha-se disponível para responder dúvidas e iniciar o planejamento do projeto.
</div>
</div>

<p class="body-text">
Continue acompanhando o status através do seu painel de parceiro.
</p>

<div class="cta-wrapper">
<a href="<?= htmlspecialchars($siteUrl) ?>/parceiro/propostas" class="cta-btn">Ver Minhas Propostas</a>
</div>
