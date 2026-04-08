<?php
/**
 * Template: Resultado de Qualificação (Parceiro)
 * Variáveis: $nomeParceiro, $status, $parecer, $siteUrl
 */
$primeiroNome = explode(' ', $nomeParceiro)[0];
$aprovado = in_array($status, ['aprovado', 'vetriks_ativo'], true);
?>
<p class="body-text">
<?php if ($aprovado): ?>
Sua qualificação foi <strong>aprovada</strong>! Você agora faz parte da nossa rede de parceiros qualificados.
<?php else: ?>
Sua qualificação foi analisada por nossa equipe. Infelizmente não foi possível aprovar seu cadastro neste momento.
<?php endif; ?>
</p>

<?php if ($aprovado): ?>
<p class="body-text">
A partir de agora, você começará a receber oportunidades compatíveis com seu perfil profissional e poderá enviar propostas para projetos qualificados.
</p>
<?php else: ?>
<p class="body-text">
Agradecemos seu interesse em fazer parte da nossa rede. Você pode atualizar seu perfil e solicitar uma nova análise no futuro.
</p>
<?php endif; ?>

<?php if (!empty($parecer)): ?>
<div class="highlight-box">
<div class="highlight-box-label">— Parecer da Equipe</div>
<div class="highlight-box-text">
<?= nl2br(htmlspecialchars($parecer)) ?>
</div>
</div>
<?php endif; ?>

<p class="body-text">
<?php if ($aprovado): ?>
Acesse seu painel de parceiro para começar a visualizar oportunidades disponíveis.
<?php else: ?>
Se tiver dúvidas, entre em contato com nossa equipe.
<?php endif; ?>
</p>

<div class="cta-wrapper">
<a href="<?= htmlspecialchars($siteUrl) ?>/parceiro/<?= $aprovado ? 'oportunidades' : 'perfil' ?>" class="cta-btn">
<?= $aprovado ? 'Ver Oportunidades' : 'Ver Meu Perfil' ?>
</a>
</div>
