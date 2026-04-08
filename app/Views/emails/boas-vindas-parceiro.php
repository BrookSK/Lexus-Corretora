<?php
/**
 * Template: Boas-vindas Parceiro
 * Variáveis: $nomeParceiro, $siteUrl
 */
$primeiroNome = explode(' ', $nomeParceiro)[0];
?>
<p class="body-text">
Seja bem-vindo à nossa plataforma! Seu cadastro foi recebido com sucesso.
</p>

<p class="body-text">
Nossa equipe de qualificação irá analisar seu perfil profissional, portfólio e documentação. Este processo pode levar alguns dias úteis.
</p>

<div class="highlight-box">
<div class="highlight-box-label">— Próximos Passos</div>
<div class="highlight-box-text">
Aguarde o resultado da qualificação. Você receberá um e-mail assim que a análise for concluída. Enquanto isso, você pode completar seu perfil e adicionar mais informações sobre seus projetos.
</div>
</div>

<p class="body-text">
Estamos ansiosos para tê-lo em nossa rede de parceiros qualificados.
</p>

<div class="cta-wrapper">
<a href="<?= htmlspecialchars($siteUrl) ?>/parceiro/dashboard" class="cta-btn">Acessar Meu Painel</a>
</div>
