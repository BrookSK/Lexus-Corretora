<?php
declare(strict_types=1);
use LEX\Core\View;

// Mapas de tradução
$statusMap = ['novo' => 'Nova', 'em_triagem' => 'Em Triagem', 'em_estruturacao' => 'Em Estruturação', 'pronto_repasse' => 'Pronto para Repasse', 'distribuido' => 'Distribuído', 'aguardando_respostas' => 'Aguardando Respostas', 'recebendo_propostas' => 'Recebendo Propostas', 'em_curadoria' => 'Em Curadoria', 'apresentado_cliente' => 'Apresentado ao Cliente', 'em_negociacao' => 'Em Negociação', 'contrato_formalizacao' => 'Formalização de Contrato', 'fechado_ganho' => 'Fechado (Ganho)', 'fechado_perda' => 'Fechado (Perda)', 'pausado' => 'Pausado', 'cancelado' => 'Cancelado'];
$urgenciaMap = ['baixa' => 'Baixa', 'media' => 'Média', 'alta' => 'Alta', 'critica' => 'Crítica'];
$complexidadeMap = ['simples' => 'Simples', 'moderada' => 'Moderada', 'complexa' => 'Complexa', 'muito_complexa' => 'Muito Complexa'];
$prioridadeMap = ['baixa' => 'Baixa', 'normal' => 'Normal', 'alta' => 'Alta', 'urgente' => 'Urgente'];
$origemMap = ['cliente' => 'Cliente', 'parceiro' => 'Parceiro', 'arquiteto' => 'Arquiteto', 'equipe' => 'Equipe', 'lead' => 'Lead/Formulário', 'importacao' => 'Importação'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo View::e($demanda['code']); ?> — Apresentação Completa</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=Jost:wght@200;300;400;500;600&display=swap" rel="stylesheet">
<style>
:root{--gold:#C9A96E;--gold-light:#E8C98A;--gold-dark:#9A7A48;--black:#0A0A0A;--dark:#111111;--dark-card:#161616;--off-white:#F5F2EC;--muted:#7A7570;--border:rgba(201,169,110,0.18);--border-light:rgba(201,169,110,0.08)}*{margin:0;padding:0;box-sizing:border-box}@page{size:A4;margin:0}body{font-family:'Jost',sans-serif;background:var(--dark);color:var(--off-white);width:210mm;margin:0 auto;font-size:9px;line-height:1.5}.header{background:var(--black);padding:20px 30px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:flex-start;position:relative}.header::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,transparent,var(--gold),var(--gold-light),var(--gold),transparent)}.logo-wordmark{font-family:'Cormorant Garamond',serif;font-size:16px;font-weight:400;letter-spacing:3px;color:var(--gold);text-transform:uppercase}.logo-tagline{font-size:6.5px;color:var(--muted);letter-spacing:2px;text-transform:uppercase;margin-top:2px}.doc-id{font-family:'Cormorant Garamond',serif;font-size:18px;font-weight:300;color:var(--off-white);letter-spacing:1px}.doc-id span{color:var(--gold)}.doc-date{font-size:6.5px;color:var(--muted);letter-spacing:1.5px;text-transform:uppercase;margin-top:3px;text-align:right}.hero{background:linear-gradient(135deg,#0D0B08 0%,#161410 50%,#0D0B08 100%);padding:20px 30px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center}.hero-title{font-family:'Cormorant Garamond',serif;font-size:24px;font-weight:300;line-height:1.1;color:var(--off-white)}.hero-label{font-size:6px;letter-spacing:2.5px;text-transform:uppercase;color:var(--gold);margin-bottom:5px}.hero-client{font-size:7.5px;color:var(--muted);margin-top:6px;letter-spacing:0.8px}.status-badge{background:rgba(201,169,110,0.08);border:1px solid var(--border);border-radius:2px;padding:6px 14px;text-align:center}.status-label{font-size:5.5px;letter-spacing:2px;text-transform:uppercase;color:var(--muted);margin-bottom:4px}.status-value{font-family:'Cormorant Garamond',serif;font-size:13px;font-weight:400;color:var(--gold);letter-spacing:1.5px;text-transform:uppercase}.content{padding:20px 30px;background:var(--dark)}.section{margin-bottom:18px;page-break-inside:avoid}.section-title{font-size:6px;letter-spacing:2.5px;text-transform:uppercase;color:var(--gold);margin-bottom:10px;padding-bottom:5px;border-bottom:1px solid var(--border)}.grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:15px}.grid-2{grid-template-columns:repeat(2,1fr)}.grid-4{grid-template-columns:repeat(4,1fr)}.info-box{background:var(--dark-card);border:1px solid var(--border-light);padding:10px 12px}.info-label{font-size:6px;letter-spacing:1.5px;text-transform:uppercase;color:var(--muted);margin-bottom:4px}.info-value{font-size:8.5px;color:var(--off-white);font-weight:400;word-break:break-word}.info-value.highlight{color:var(--gold);font-weight:500}.text-block{background:var(--dark-card);border:1px solid var(--border-light);padding:12px 14px;white-space:pre-wrap;font-size:8px;line-height:1.6;color:rgba(245,242,236,0.85)}.photos-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px}.photo-card{background:var(--dark-card);border:1px solid var(--border-light);overflow:hidden;page-break-inside:avoid;display:flex;flex-direction:column}.photo-img{width:100%;height:auto;object-fit:contain;display:block;max-height:400px;background:var(--dark-card)}.photo-placeholder{height:200px;background:linear-gradient(135deg,#1a1814,#201e1a);display:flex;align-items:center;justify-content:center;color:var(--gold-dark);font-size:18px;opacity:0.5}.photo-caption{padding:8px 10px;font-size:7px;color:rgba(245,242,236,0.7);line-height:1.4;border-top:1px solid var(--border-light);background:rgba(0,0,0,0.3)}.timeline{position:relative;padding-left:18px}.timeline::before{content:'';position:absolute;left:5px;top:5px;bottom:5px;width:1px;background:linear-gradient(to bottom,var(--gold-dark),transparent)}.timeline-item{position:relative;margin-bottom:10px;padding-bottom:10px;border-bottom:1px solid rgba(255,255,255,0.03)}.timeline-item:last-child{border-bottom:none;margin-bottom:0}.timeline-dot{position:absolute;left:-15px;top:3px;width:5px;height:5px;border:1px solid var(--gold-dark);background:var(--dark);transform:rotate(45deg)}.timeline-date{font-size:6px;color:var(--gold-dark);letter-spacing:1.2px;margin-bottom:2px;text-transform:uppercase}.timeline-event{font-size:7.5px;color:var(--off-white);font-weight:400}.timeline-actor{font-size:6px;color:var(--muted);margin-top:1px}.footer{background:var(--black);border-top:1px solid var(--border);padding:14px 30px;display:flex;justify-content:space-between;align-items:center;font-size:6px;color:var(--muted)}.footer-brand{font-family:'Cormorant Garamond',serif;font-size:10px;letter-spacing:3px;color:var(--gold);text-transform:uppercase}.print-btn{position:fixed;bottom:20px;right:20px;background:var(--gold);color:var(--black);border:none;padding:12px 24px;font-family:'Jost',sans-serif;font-size:11px;letter-spacing:1.5px;text-transform:uppercase;cursor:pointer;font-weight:600;z-index:1000;transition:all 0.3s;border-radius:4px;box-shadow:0 4px 12px rgba(201,169,110,0.4)}.print-btn:hover{background:var(--gold-light);transform:translateY(-2px);box-shadow:0 6px 16px rgba(201,169,110,0.6)}.print-btn:active{transform:translateY(0)}.toolbar{position:fixed;top:0;left:0;right:0;background:rgba(10,10,10,0.95);backdrop-filter:blur(10px);padding:12px 30px;display:flex;justify-content:space-between;align-items:center;z-index:999;border-bottom:1px solid var(--border)}.toolbar-title{font-family:'Cormorant Garamond',serif;font-size:14px;color:var(--gold);letter-spacing:2px}.toolbar-btn{background:var(--gold);color:var(--black);border:none;padding:8px 20px;font-family:'Jost',sans-serif;font-size:10px;letter-spacing:1.2px;text-transform:uppercase;cursor:pointer;font-weight:600;border-radius:3px;transition:all 0.2s;margin-left:10px}.toolbar-btn:hover{background:var(--gold-light);transform:scale(1.05)}.toolbar-btn.secondary{background:transparent;border:1px solid var(--gold);color:var(--gold)}.toolbar-btn.secondary:hover{background:rgba(201,169,110,0.1)}body{margin-top:60px}@media print{body{-webkit-print-color-adjust:exact;print-color-adjust:exact;margin-top:0}.print-btn,.toolbar{display:none}}
</style>
</head>
<body>

<!-- Toolbar de Ações -->
<div class="toolbar">
<div class="toolbar-title">📄 Apresentação da Demanda — <?php echo View::e($demanda['code']); ?></div>
<div>
<button class="toolbar-btn secondary" onclick="window.history.back()">← Voltar</button>
<button class="toolbar-btn" onclick="window.print()">⬇ Exportar PDF</button>
</div>
</div>

<!-- Botão Flutuante -->
<button class="print-btn" onclick="window.print()" title="Exportar como PDF">⬇ Exportar PDF</button>

<header class="header">
<div><div class="logo-wordmark">Lexus</div><div class="logo-tagline">Estruturação Estratégica de Obras</div></div>
<div><div class="doc-id"><span><?php echo View::e(explode('-', $demanda['code'])[0]); ?></span>-<?php echo View::e(explode('-', $demanda['code'])[1] ?? ''); ?></div><div class="doc-date">Gerado em <?php echo date('d/m/Y H:i'); ?></div></div>
</header>

<div class="hero">
<div><div class="hero-label">— Apresentação Completa da Demanda</div><div class="hero-title"><?php echo View::e($demanda['title']); ?></div><div class="hero-client">Cliente: <?php echo View::e($demanda['cliente_nome'] ?? 'A definir'); ?> · Origem: <?php echo View::e($origemMap[$demanda['origin']] ?? $demanda['origin']); ?></div></div>
<div class="status-badge"><div class="status-label">Status Atual</div><div class="status-value"><?php echo View::e($statusMap[$demanda['status']] ?? $demanda['status']); ?></div></div>
</div>

<div class="content">

<!-- INFORMAÇÕES GERAIS -->
<div class="section">
<div class="section-title">— Informações Gerais</div>
<div class="grid-4">
<div class="info-box"><div class="info-label">Código</div><div class="info-value highlight"><?php echo View::e($demanda['code']); ?></div></div>
<div class="info-box"><div class="info-label">Status</div><div class="info-value"><?php echo View::e($statusMap[$demanda['status']] ?? $demanda['status']); ?></div></div>
<div class="info-box"><div class="info-label">Urgência</div><div class="info-value highlight"><?php echo View::e($urgenciaMap[$demanda['urgency'] ?? 'media'] ?? 'Média'); ?></div></div>
<div class="info-box"><div class="info-label">Prioridade</div><div class="info-value"><?php echo View::e($prioridadeMap[$demanda['priority'] ?? 'normal'] ?? 'Normal'); ?></div></div>
<div class="info-box"><div class="info-label">Origem</div><div class="info-value"><?php echo View::e($origemMap[$demanda['origin']] ?? $demanda['origin']); ?></div></div>
<div class="info-box"><div class="info-label">Complexidade</div><div class="info-value"><?php echo View::e($complexidadeMap[$demanda['complexity'] ?? 'moderada'] ?? 'Moderada'); ?></div></div>
<div class="info-box"><div class="info-label">Score</div><div class="info-value"><?php echo (int)($demanda['score'] ?? 0); ?> pontos</div></div>
<div class="info-box"><div class="info-label">Criado em</div><div class="info-value"><?php echo date('d/m/Y H:i', strtotime($demanda['created_at'])); ?></div></div>
<?php if (!empty($demanda['updated_at']) && $demanda['updated_at'] !== $demanda['created_at']): ?>
<div class="info-box"><div class="info-label">Última Atualização</div><div class="info-value"><?php echo date('d/m/Y H:i', strtotime($demanda['updated_at'])); ?></div></div>
<?php endif; ?>
<?php if (!empty($demanda['pending_review'])): ?>
<div class="info-box"><div class="info-label">Status de Revisão</div><div class="info-value highlight">⏳ Aguardando Revisão do Cliente</div></div>
<?php endif; ?>
</div>
</div>

<!-- CLIENTE E RESPONSÁVEL -->
<div class="section">
<div class="section-title">— Cliente e Responsável</div>
<div class="grid">
<div class="info-box"><div class="info-label">Cliente</div><div class="info-value"><?php echo View::e($demanda['cliente_nome'] ?? 'Não atribuído'); ?></div></div>
<div class="info-box"><div class="info-label">E-mail do Cliente</div><div class="info-value"><?php echo View::e($demanda['cliente_email'] ?? '—'); ?></div></div>
<div class="info-box"><div class="info-label">Telefone do Cliente</div><div class="info-value"><?php echo View::e($demanda['cliente_phone'] ?? '—'); ?></div></div>
<div class="info-box"><div class="info-label">Responsável Interno</div><div class="info-value"><?php echo View::e($demanda['responsavel_nome'] ?? 'Não atribuído'); ?></div></div>
<?php if (!empty($demanda['parceiro_originador_id'])): ?>
<div class="info-box"><div class="info-label">Parceiro Originador</div><div class="info-value">ID: <?php echo (int)$demanda['parceiro_originador_id']; ?></div></div>
<?php endif; ?>
<?php if (!empty($demanda['is_repasse'])): ?>
<div class="info-box"><div class="info-label">Tipo</div><div class="info-value highlight">📤 Repasse de Parceiro</div></div>
<?php if (!empty($demanda['repasse_status'])): ?>
<div class="info-box"><div class="info-label">Status do Repasse</div><div class="info-value"><?php echo View::e(ucfirst(str_replace('_', ' ', $demanda['repasse_status']))); ?></div></div>
<?php endif; ?>
<?php endif; ?>
</div>
</div>

<!-- LOCALIZAÇÃO E CARACTERÍSTICAS -->
<div class="section">
<div class="section-title">— Localização e Características do Imóvel</div>
<div class="grid">
<div class="info-box"><div class="info-label">Cidade</div><div class="info-value highlight"><?php echo View::e($demanda['city'] ?? '—'); ?></div></div>
<div class="info-box"><div class="info-label">Estado</div><div class="info-value"><?php echo View::e($demanda['state'] ?? '—'); ?></div></div>
<div class="info-box"><div class="info-label">País</div><div class="info-value"><?php echo View::e($demanda['country'] ?? 'Brasil'); ?></div></div>
<div class="info-box"><div class="info-label">Categoria</div><div class="info-value"><?php echo View::e($demanda['category'] ?? '—'); ?></div></div>
<div class="info-box"><div class="info-label">Subcategoria</div><div class="info-value"><?php echo View::e($demanda['subcategory'] ?? '—'); ?></div></div>
<div class="info-box"><div class="info-label">Tipo de Obra</div><div class="info-value"><?php echo View::e($demanda['work_type'] ?? '—'); ?></div></div>
<div class="info-box"><div class="info-label">Área (m²)</div><div class="info-value highlight"><?php echo $demanda['area_sqm'] ? number_format((float)$demanda['area_sqm'], 2, ',', '.') . ' m²' : '—'; ?></div></div>
<div class="info-box"><div class="info-label">Fase Atual</div><div class="info-value"><?php echo View::e($demanda['current_phase'] ?? '—'); ?></div></div>
<div class="info-box"><div class="info-label">Tipo de Contratação</div><div class="info-value"><?php echo View::e($demanda['hiring_type'] ?? '—'); ?></div></div>
</div>
</div>

<?php if (!empty($demanda['address'])): ?>
<div class="section">
<div class="section-title">— Endereço Completo</div>
<div class="text-block"><?php echo View::e($demanda['address']); ?></div>
</div>
<?php endif; ?>

<!-- ORÇAMENTO E PRAZO -->
<div class="section">
<div class="section-title">— Orçamento e Prazo</div>
<div class="grid">
<div class="info-box"><div class="info-label">Orçamento Mínimo</div><div class="info-value highlight"><?php echo !empty($demanda['budget_min']) ? 'R$ ' . number_format((float)$demanda['budget_min'], 2, ',', '.') : '—'; ?></div></div>
<div class="info-box"><div class="info-label">Orçamento Máximo</div><div class="info-value highlight"><?php echo !empty($demanda['budget_max']) ? 'R$ ' . number_format((float)$demanda['budget_max'], 2, ',', '.') : '—'; ?></div></div>
<div class="info-box"><div class="info-label">Moeda</div><div class="info-value"><?php echo View::e($demanda['currency_code'] ?? 'BRL'); ?></div></div>
<div class="info-box"><div class="info-label">Prazo Desejado</div><div class="info-value"><?php echo !empty($demanda['desired_deadline']) ? date('d/m/Y', strtotime($demanda['desired_deadline'])) : '—'; ?></div></div>
</div>
</div>

<!-- REQUISITOS DO PROJETO -->
<div class="section">
<div class="section-title">— Requisitos do Projeto</div>
<div class="grid">
<div class="info-box"><div class="info-label">Possui Projeto?</div><div class="info-value"><?php echo !empty($demanda['has_project']) ? 'Sim' : 'Não'; ?></div></div>
<div class="info-box"><div class="info-label">Possui Arquiteto?</div><div class="info-value"><?php echo !empty($demanda['has_architect']) ? 'Sim' : 'Não'; ?></div></div>
<div class="info-box"><div class="info-label">Aceita Múltiplas Propostas?</div><div class="info-value"><?php echo !empty($demanda['wants_multiple_proposals']) ? 'Sim' : 'Não'; ?></div></div>
</div>
</div>

<!-- MEMORIAL DESCRITIVO PROFISSIONAL -->
<?php if (!empty($descricaoFormal)): ?>
<div class="section">
<div class="section-title">— Memorial Descritivo do Projeto</div>
<div style="background:rgba(201,169,110,0.05);border:1px solid var(--border);padding:8px 12px;margin-bottom:8px;font-size:6.5px;color:var(--gold-dark);letter-spacing:1px">
ℹ️ Documento técnico elaborado com base nas informações fornecidas pelo cliente e observações de campo
</div>
<div class="text-block" style="font-size:8.5px;line-height:1.7"><?php echo nl2br(View::e($descricaoFormal)); ?></div>
</div>
<?php endif; ?>

<?php if (!empty($demanda['internal_notes'])): ?>
<div class="section">
<div class="section-title">— Notas Internas (Uso Exclusivo da Equipe)</div>
<div class="text-block"><?php echo nl2br(View::e($demanda['internal_notes'])); ?></div>
</div>
<?php endif; ?>

<?php if (!empty($demanda['ideal_partner_profile'])): ?>
<div class="section">
<div class="section-title">— Perfil Ideal de Parceiro</div>
<div class="text-block"><?php echo nl2br(View::e($demanda['ideal_partner_profile'])); ?></div>
</div>
<?php endif; ?>

<!-- FOTOS E VÍDEOS -->
<?php if (!empty($arquivos)): ?>
<div class="section">
<div class="section-title">— Fotos e Vídeos do Projeto (<?php echo count($arquivos); ?> arquivo(s))</div>
<div class="photos-grid">
<?php foreach ($arquivos as $index => $arq): ?>
<div class="photo-card">
<?php if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $arq['file_path'])): ?>
<img src="<?php echo View::e($arq['file_path']); ?>" class="photo-img" alt="Foto <?php echo $index + 1; ?>">
<?php else: ?>
<div class="photo-placeholder">▤</div>
<?php endif; ?>
<div class="photo-caption">
<?php echo View::e(basename($arq['file_path'])); ?>
<?php if (!empty($arq['caption'])): ?>
<br><strong>Legenda:</strong> <?php echo View::e($arq['caption']); ?>
<?php endif; ?>
<?php if (!empty($arq['uploaded_by_type'])): ?>
<br><span style="opacity:0.6">Enviado por: <?php echo View::e(ucfirst($arq['uploaded_by_type'])); ?></span>
<?php endif; ?>
</div>
</div>
<?php endforeach; ?>
</div>
</div>
<?php endif; ?>

<!-- ESTATÍSTICAS DE PROPOSTAS -->
<?php if (!empty($propostas) && is_array($propostas) && count($propostas) > 0): ?>
<div class="section">
<div class="section-title">— Propostas Recebidas (<?php echo count($propostas); ?> proposta(s))</div>
<div class="grid">
<?php 
$propostasAceitas = array_filter($propostas, fn($p) => ($p['status'] ?? '') === 'selecionada');
$propostasShortlist = array_filter($propostas, fn($p) => ($p['status'] ?? '') === 'shortlist');
$propostasEnviadas = array_filter($propostas, fn($p) => ($p['status'] ?? '') === 'enviada');
?>
<div class="info-box"><div class="info-label">Total de Propostas</div><div class="info-value highlight"><?php echo count($propostas); ?></div></div>
<div class="info-box"><div class="info-label">Selecionadas</div><div class="info-value"><?php echo count($propostasAceitas); ?></div></div>
<div class="info-box"><div class="info-label">Em Shortlist</div><div class="info-value"><?php echo count($propostasShortlist); ?></div></div>
<div class="info-box"><div class="info-label">Aguardando Análise</div><div class="info-value"><?php echo count($propostasEnviadas); ?></div></div>
</div>
</div>
<?php endif; ?>

<!-- TIMELINE -->
<?php if (!empty($timeline)): ?>
<div class="section">
<div class="section-title">— Timeline Completa da Demanda</div>
<div class="timeline">
<?php foreach ($timeline as $event): ?>
<div class="timeline-item">
<div class="timeline-dot"></div>
<div class="timeline-date"><?php echo date('d/m/Y H:i', strtotime($event['created_at'])); ?></div>
<div class="timeline-event"><?php echo View::e($event['description'] ?? $event['event_type']); ?></div>
<div class="timeline-actor"><?php echo View::e(ucfirst($event['actor_type'] ?? 'Sistema')); ?><?php if (!empty($event['actor_id'])): ?> · ID: <?php echo (int)$event['actor_id']; ?><?php endif; ?></div>
</div>
<?php endforeach; ?>
</div>
</div>
<?php endif; ?>

</div>

<footer class="footer">
<div><div class="footer-brand">Lexus</div><div style="font-size:5.5px;margin-top:2px;letter-spacing:1.5px">Estruturação Estratégica de Obras</div></div>
<div style="text-align:center;opacity:0.5">Documento Confidencial · Uso Interno</div>
<div style="text-align:right"><?php echo View::e($demanda['code']); ?> · Gerado em <?php echo date('d/m/Y H:i'); ?><br><span style="color:var(--gold-dark)">lexuscorretora.com.br</span></div>
</footer>

</body>
</html>
