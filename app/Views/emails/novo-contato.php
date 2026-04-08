<?php
/**
 * Template: Novo Contato (Equipe)
 * Variáveis: $nomeRemetente, $emailRemetente, $mensagem, $siteUrl
 */
?>
<p class="body-text">
Um novo contato foi recebido através do formulário do site.
</p>

<div class="info-card">
<div class="info-card-title">— Dados do Contato</div>
<div class="info-row">
<span class="info-key">Nome</span>
<span class="info-val"><?= htmlspecialchars($nomeRemetente) ?></span>
</div>
<div class="info-row">
<span class="info-key">E-mail</span>
<span class="info-val"><?= htmlspecialchars($emailRemetente) ?></span>
</div>
</div>

<div class="highlight-box">
<div class="highlight-box-label">— Mensagem</div>
<div class="highlight-box-text">
<?= nl2br(htmlspecialchars($mensagem)) ?>
</div>
</div>

<p class="body-text">
Responda este contato o mais breve possível para manter um bom relacionamento com potenciais clientes.
</p>
