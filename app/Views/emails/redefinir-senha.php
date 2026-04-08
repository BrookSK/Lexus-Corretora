<?php
/**
 * Template: Redefinição de Senha
 * Variáveis: $nome, $link, $siteUrl
 */
$primeiroNome = explode(' ', $nome)[0];
?>
<p class="body-text">
Recebemos uma solicitação para redefinir a senha da sua conta.
</p>

<p class="body-text">
Se você não fez esta solicitação, pode ignorar este e-mail com segurança. Sua senha permanecerá inalterada.
</p>

<div class="highlight-box">
<div class="highlight-box-label">— Link de Redefinição</div>
<div class="highlight-box-text">
Este link é válido por 2 horas. Após este período, será necessário solicitar um novo link.
</div>
</div>

<p class="body-text">
Clique no botão abaixo para criar uma nova senha:
</p>

<div class="cta-wrapper">
<a href="<?= htmlspecialchars($link) ?>" class="cta-btn">Redefinir Senha</a>
</div>

<p class="body-text" style="font-size: 11px; color: rgba(245, 242, 236, 0.50); margin-top: 20px;">
Se o botão não funcionar, copie e cole este link no seu navegador:<br>
<?= htmlspecialchars($link) ?>
</p>
