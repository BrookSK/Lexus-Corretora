<?php
/**
 * Template: Nova Demanda (Cliente)
 * Variáveis: $nomeCliente, $codigoDemanda, $tituloDemanda, $siteUrl
 */
$primeiroNome = explode(' ', $nomeCliente)[0];
?>
<p class="body-text">
Recebemos sua demanda com sucesso e ela já está sendo analisada por nossa equipe de especialistas.
</p>

<p class="body-text">
Em breve, você receberá propostas de parceiros qualificados que atendem ao perfil do seu projeto.
</p>

<div class="info-card">
<div class="info-card-title">— Dados da Demanda</div>
<div class="info-row">
<span class="info-key">Código</span>
<span class="info-val gold"><?= htmlspecialchars($codigoDemanda) ?></span>
</div>
<div class="info-row">
<span class="info-key">Título</span>
<span class="info-val"><?= htmlspecialchars($tituloDemanda) ?></span>
</div>
</div>

<p class="body-text">
Você pode acompanhar o andamento da sua demanda e receber propostas através do seu painel de cliente.
</p>

<div class="cta-wrapper">
<a href="<?= htmlspecialchars($siteUrl) ?>/cliente/demandas" class="cta-btn">Acompanhar Demanda</a>
</div>
