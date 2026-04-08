<?php
/**
 * Template Base para E-mails
 * Variáveis esperadas:
 * - $emailCategory: Categoria do e-mail (ex: "Nova Demanda", "Oportunidade")
 * - $emailTitleLine1: Primeira linha do título
 * - $emailTitleLine2: Segunda linha do título (em itálico)
 * - $emailSubtitle: Subtítulo do hero
 * - $recipientFirstName: Primeiro nome do destinatário
 * - $bodyContent: Conteúdo HTML do corpo do e-mail
 * - $documentCode: Código do documento (opcional)
 * - $siteUrl: URL do site
 * - $siteName: Nome do sistema
 */

$siteUrl = $siteUrl ?? \LEX\Core\SistemaConfig::url();
$siteName = $siteName ?? \LEX\Core\SistemaConfig::nome();
$documentCode = $documentCode ?? '';
$currentYear = date('Y');
?><!DOCTYPE html>
<html lang="pt-BR" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="format-detection" content="telephone=no">
<title><?= htmlspecialchars($emailCategory ?? 'Notificação') ?></title>
<style>
/* ── RESET ── */
body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }
body { margin: 0 !important; padding: 0 !important; width: 100% !important; }

/* ── BASE ── */
body {
    background-color: #0D0B09;
    font-family: 'Jost', -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
}
.email-wrapper {
    background-color: #0D0B09;
    padding: 40px 16px;
}
.email-container {
    max-width: 600px;
    margin: 0 auto;
    background-color: #111111;
    border: 1px solid rgba(201, 169, 110, 0.15);
}

/* ── TOP ACCENT LINE ── */
.accent-line {
    height: 2px;
    background: linear-gradient(90deg, transparent 0%, #C9A96E 30%, #E8C98A 50%, #C9A96E 70%, transparent 100%);
    font-size: 0;
    line-height: 0;
}

/* ── HEADER ── */
.email-header {
    background-color: #0A0A0A;
    padding: 28px 40px 24px;
    border-bottom: 1px solid rgba(201, 169, 110, 0.12);
}
.logo-wordmark {
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 20px;
    font-weight: 400;
    letter-spacing: 5px;
    color: #C9A96E;
    text-transform: uppercase;
    text-decoration: none;
    display: block;
    margin-bottom: 4px;
}
.logo-tagline {
    font-size: 9px;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    color: #7A7570;
}

/* ── HERO ── */
.email-hero {
    background-color: #0F0D0B;
    padding: 36px 40px 30px;
    border-bottom: 1px solid rgba(201, 169, 110, 0.10);
    position: relative;
}
.hero-eyebrow {
    font-size: 9px;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: #C9A96E;
    margin-bottom: 10px;
}
.hero-title {
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 28px;
    font-weight: 400;
    line-height: 1.2;
    color: #F5F2EC;
    margin-bottom: 14px;
}
.hero-title em {
    font-style: italic;
    color: #C9A96E;
}
.hero-subtitle {
    font-size: 13px;
    color: rgba(245, 242, 236, 0.60);
    line-height: 1.6;
    font-weight: 300;
}

/* ── GREETING ── */
.email-greeting {
    padding: 28px 40px 0;
}
.greeting-text {
    font-size: 14px;
    color: #F5F2EC;
    line-height: 1.7;
    font-weight: 300;
}
.greeting-name {
    color: #C9A96E;
    font-weight: 500;
}

/* ── BODY ── */
.email-body {
    padding: 20px 40px 28px;
}
.body-text {
    font-size: 13px;
    color: rgba(245, 242, 236, 0.75);
    line-height: 1.75;
    font-weight: 300;
    margin-bottom: 18px;
}
.body-text:last-child { margin-bottom: 0; }

/* ── INFO CARD / DEMAND SUMMARY ── */
.info-card {
    background-color: #161616;
    border: 1px solid rgba(201, 169, 110, 0.15);
    border-left: 2px solid #C9A96E;
    margin: 24px 0;
    padding: 20px 22px;
}
.info-card-title {
    font-size: 8px;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    color: #C9A96E;
    margin-bottom: 14px;
}
.info-row {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    padding: 7px 0;
    border-bottom: 1px solid rgba(255,255,255,0.04);
    font-size: 12px;
}
.info-row:last-child { border-bottom: none; }
.info-key {
    color: #7A7570;
    font-size: 11px;
    letter-spacing: 0.5px;
}
.info-val {
    color: #F5F2EC;
    font-weight: 400;
    text-align: right;
}
.info-val.gold { color: #C9A96E; font-weight: 500; }

/* ── DIVIDER ── */
.section-divider {
    margin: 24px 40px;
    border: none;
    border-top: 1px solid rgba(201, 169, 110, 0.12);
}

/* ── HIGHLIGHT BOX ── */
.highlight-box {
    background-color: rgba(201, 169, 110, 0.05);
    border: 1px solid rgba(201, 169, 110, 0.18);
    margin: 0 0 20px;
    padding: 16px 20px;
}
.highlight-box-label {
    font-size: 8px;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    color: #C9A96E;
    margin-bottom: 8px;
}
.highlight-box-text {
    font-size: 12.5px;
    color: rgba(245, 242, 236, 0.70);
    line-height: 1.65;
    font-weight: 300;
}

/* ── CTA BUTTON ── */
.cta-wrapper {
    padding: 4px 0 28px;
    text-align: left;
}
.cta-btn {
    display: inline-block;
    background-color: #C9A96E;
    color: #0A0A0A !important;
    text-decoration: none;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    padding: 13px 30px;
    font-family: 'Jost', Arial, sans-serif;
}
.cta-btn-outline {
    display: inline-block;
    background-color: transparent;
    color: #C9A96E !important;
    text-decoration: none;
    font-size: 10px;
    font-weight: 500;
    letter-spacing: 2px;
    text-transform: uppercase;
    padding: 12px 28px;
    border: 1px solid rgba(201, 169, 110, 0.50);
    margin-left: 12px;
    font-family: 'Jost', Arial, sans-serif;
}

/* ── SIGNATURE ── */
.email-signature {
    padding: 24px 40px;
    border-top: 1px solid rgba(201, 169, 110, 0.10);
    background-color: #0F0D0B;
}
.sig-closing {
    font-size: 13px;
    color: rgba(245, 242, 236, 0.60);
    margin-bottom: 14px;
    font-weight: 300;
}
.sig-name {
    font-family: Georgia, serif;
    font-size: 16px;
    font-weight: 400;
    color: #F5F2EC;
    margin-bottom: 2px;
}
.sig-role {
    font-size: 9px;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: #C9A96E;
    margin-bottom: 12px;
}
.sig-divider {
    width: 32px;
    height: 1px;
    background-color: #9A7A48;
    margin: 12px 0;
}
.sig-contact {
    font-size: 11px;
    color: #7A7570;
    line-height: 1.9;
}
.sig-contact a {
    color: #C9A96E;
    text-decoration: none;
}

/* ── FOOTER ── */
.email-footer {
    background-color: #0A0A0A;
    padding: 18px 40px;
    border-top: 1px solid rgba(201, 169, 110, 0.10);
}
.footer-brand {
    font-family: Georgia, serif;
    font-size: 11px;
    letter-spacing: 4px;
    color: #C9A96E;
    text-transform: uppercase;
    margin-bottom: 6px;
}
.footer-links {
    font-size: 9px;
    color: #7A7570;
    letter-spacing: 0.5px;
    margin-bottom: 10px;
}
.footer-links a {
    color: #7A7570;
    text-decoration: none;
    margin-right: 12px;
}
.footer-legal {
    font-size: 9px;
    color: rgba(122, 117, 112, 0.50);
    line-height: 1.7;
}

/* ── BOTTOM ACCENT ── */
.accent-line-bottom {
    height: 1px;
    background: linear-gradient(90deg, transparent 0%, #9A7A48 50%, transparent 100%);
    font-size: 0;
    line-height: 0;
}

/* ── RESPONSIVE ── */
@media only screen and (max-width: 620px) {
    .email-header,
    .email-hero,
    .email-greeting,
    .email-body,
    .email-signature,
    .email-footer { padding-left: 24px !important; padding-right: 24px !important; }
    .section-divider { margin-left: 24px; margin-right: 24px; }
    .hero-title { font-size: 22px !important; }
    .cta-btn-outline { margin-left: 0 !important; margin-top: 10px !important; display: block !important; }
    .info-row { flex-direction: column; gap: 2px; }
    .info-val { text-align: left !important; }
}
</style>
</head>
<body>
<div class="email-wrapper">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td>
<div class="email-container">

<!-- ══ ACCENT TOP ══ -->
<div class="accent-line"></div>

<!-- ══ HEADER ══ -->
<div class="email-header">
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
<td>
<a href="<?= htmlspecialchars($siteUrl) ?>" class="logo-wordmark"><?= htmlspecialchars($siteName) ?></a>
<div class="logo-tagline">Estruturação Estratégica de Obras</div>
</td>
<td align="right" style="font-size:10px; color:#7A7570; vertical-align:bottom; letter-spacing:1px;">
<?= htmlspecialchars($documentCode) ?>
</td>
</tr>
</table>
</div>

<!-- ══ HERO ══ -->
<div class="email-hero">
<div class="hero-eyebrow">— <?= htmlspecialchars($emailCategory) ?></div>
<div class="hero-title">
<?= htmlspecialchars($emailTitleLine1) ?><br>
<em><?= htmlspecialchars($emailTitleLine2) ?></em>
</div>
<div class="hero-subtitle"><?= htmlspecialchars($emailSubtitle) ?></div>
</div>

<!-- ══ GREETING ══ -->
<div class="email-greeting">
<p class="greeting-text">Olá, <span class="greeting-name"><?= htmlspecialchars($recipientFirstName) ?></span>,</p>
</div>

<!-- ══ BODY ══ -->
<div class="email-body">
<?= $bodyContent ?>
</div>

<!-- ══ SIGNATURE ══ -->
<div class="email-signature">
<div class="sig-closing">Atenciosamente,</div>
<div class="sig-name"><?= htmlspecialchars($siteName) ?></div>
<div class="sig-role">Equipe de Relacionamento</div>
<div class="sig-divider"></div>
<div class="sig-contact">
<a href="<?= htmlspecialchars($siteUrl) ?>"><?= htmlspecialchars(str_replace(['http://', 'https://'], '', $siteUrl)) ?></a>
</div>
</div>

<!-- ══ FOOTER ══ -->
<div class="email-footer">
<div class="footer-brand"><?= htmlspecialchars($siteName) ?></div>
<div class="footer-links">
<a href="<?= htmlspecialchars($siteUrl) ?>">Site</a>
<a href="<?= htmlspecialchars($siteUrl) ?>/como-funciona">Como Funciona</a>
<a href="<?= htmlspecialchars($siteUrl) ?>/contato">Contato</a>
</div>
<div class="footer-legal">
Você recebeu este e-mail pois está cadastrado na plataforma <?= htmlspecialchars($siteName) ?>.<br>
<?= htmlspecialchars($siteName) ?> · São Paulo, SP · <?= $currentYear ?><br>
Este é um e-mail automático. Por favor, não responda diretamente.
</div>
</div>

<!-- ══ ACCENT BOTTOM ══ -->
<div class="accent-line-bottom"></div>

</div><!-- /.email-container -->
</td></tr>
</table>
</div>
</body>
</html>
