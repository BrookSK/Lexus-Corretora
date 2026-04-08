<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};

/**
 * Detalhe de uma demanda — painel do cliente
 * Variáveis: $demanda (array), $timeline (array), $propostas (array)
 */

$statusBadge = [
    'novo' => 'badge-gray', 'cadastrado' => 'badge-gray', 'rascunho' => 'badge-gray', 'pendente' => 'badge-gray',
    'em_triagem' => 'badge-blue', 'em_estruturacao' => 'badge-blue', 'em_analise' => 'badge-blue', 'em_qualificacao' => 'badge-blue',
    'distribuido' => 'badge-gold', 'enviada' => 'badge-gold', 'pronto_repasse' => 'badge-gold',
    'aprovado' => 'badge-green', 'vetriks_ativo' => 'badge-green', 'selecionada' => 'badge-green', 'confirmada' => 'badge-green', 'recebida' => 'badge-green',
    'fechado_perda' => 'badge-red', 'reprovado' => 'badge-red', 'cancelado' => 'badge-red', 'descartada' => 'badge-red',
];

$urgenciaBadge = [
    'baixa' => 'badge-gray', 'media' => 'badge-blue', 'alta' => 'badge-gold', 'critica' => 'badge-red',
];
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e($demanda['code']); ?> — <?php echo View::e($demanda['title']); ?></h1>
    <p class="section-subtitle">
      <span class="badge <?php echo $statusBadge[$demanda['status']] ?? 'badge-gray'; ?>">
        <?php echo View::e(I18n::t('status.' . $demanda['status']) ?: $demanda['status']); ?>
      </span>
      <span class="badge <?php echo $urgenciaBadge[$demanda['urgency'] ?? 'media'] ?? 'badge-gray'; ?>" style="margin-left:8px">
        <?php echo View::e(ucfirst($demanda['urgency'] ?? 'media')); ?>
      </span>
      <?php if (!empty($demanda['pending_review'])): ?>
      <span class="badge badge-gold" style="margin-left:8px">⏳ Aguardando Revisão</span>
      <?php endif; ?>
    </p>
  </div>
  <div style="display:flex;gap:8px">
    <a href="/cliente/demandas/<?php echo (int)$demanda['id']; ?>/editar" class="btn btn-primary">Editar Demanda</a>
    <a href="/cliente/demandas" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
  </div>
</div>

<!-- Informações Gerais -->
<div class="cards-grid" style="grid-template-columns:1fr 1fr">
  <div class="card">
    <h3 class="card-title"><?php echo View::e(I18n::t('demanda.dados_obra')); ?></h3>
    <table style="width:100%;font-size:.88rem;margin-top:12px">
      <tr><td style="color:var(--text-muted);padding:6px 0;width:40%"><?php echo View::e(I18n::t('demanda.tipo_obra')); ?></td><td style="padding:6px 0"><?php echo View::e($demanda['work_type'] ?: ($demanda['category'] ?? '—')); ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:6px 0"><?php echo View::e(I18n::t('demanda.localizacao')); ?></td><td style="padding:6px 0"><?php echo View::e(($demanda['city'] ?? '') . ', ' . ($demanda['state'] ?? '')); ?></td></tr>
      <?php if (!empty($demanda['address'])): ?>
      <tr><td style="color:var(--text-muted);padding:6px 0">Endereço</td><td style="padding:6px 0"><?php echo View::e($demanda['address']); ?></td></tr>
      <?php endif; ?>
      <tr><td style="color:var(--text-muted);padding:6px 0"><?php echo View::e(I18n::t('demanda.metragem')); ?></td><td style="padding:6px 0"><?php echo $demanda['area_sqm'] ? View::e(number_format((float)$demanda['area_sqm'], 2, ',', '.')) . ' m²' : '—'; ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:6px 0"><?php echo View::e(I18n::t('demanda.prazo_desejado')); ?></td><td style="padding:6px 0"><?php echo $demanda['desired_deadline'] ? View::e(date('d/m/Y', strtotime($demanda['desired_deadline']))) : '—'; ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:6px 0">Criada em</td><td style="padding:6px 0"><?php echo View::e(date('d/m/Y H:i', strtotime($demanda['created_at']))); ?></td></tr>
    </table>
  </div>

  <div class="card">
    <h3 class="card-title"><?php echo View::e(I18n::t('demanda.orcamento')); ?></h3>
    <table style="width:100%;font-size:.88rem;margin-top:12px">
      <tr><td style="color:var(--text-muted);padding:6px 0;width:40%">Mínimo</td><td style="padding:6px 0"><?php echo $demanda['budget_min'] ? 'R$ ' . View::e(number_format((float)$demanda['budget_min'], 2, ',', '.')) : '—'; ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:6px 0">Máximo</td><td style="padding:6px 0"><?php echo $demanda['budget_max'] ? 'R$ ' . View::e(number_format((float)$demanda['budget_max'], 2, ',', '.')) : '—'; ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:6px 0"><?php echo View::e(I18n::t('demanda.tem_projeto')); ?></td><td style="padding:6px 0"><?php echo ($demanda['has_project'] ?? 0) ? View::e(I18n::t('geral.sim')) : View::e(I18n::t('geral.nao')); ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:6px 0"><?php echo View::e(I18n::t('demanda.tem_arquiteto')); ?></td><td style="padding:6px 0"><?php echo ($demanda['has_architect'] ?? 0) ? View::e(I18n::t('geral.sim')) : View::e(I18n::t('geral.nao')); ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:6px 0"><?php echo View::e(I18n::t('demanda.multiplas_prop')); ?></td><td style="padding:6px 0"><?php echo ($demanda['wants_multiple_proposals'] ?? 1) ? View::e(I18n::t('geral.sim')) : View::e(I18n::t('geral.nao')); ?></td></tr>
    </table>
  </div>
</div>

<!-- Descrição -->
<?php if (!empty($demanda['description'])): ?>
<div class="card" style="margin-bottom:24px">
  <h3 class="card-title"><?php echo View::e(I18n::t('demanda.descricao')); ?></h3>
  <p style="margin-top:12px;font-size:.88rem;line-height:1.6;white-space:pre-wrap"><?php echo View::e($demanda['description']); ?></p>
</div>
<?php endif; ?>

<!-- Fotos e Vídeos do Projeto -->
<?php if (!empty($arquivos)): ?>
<div style="margin-bottom:32px">
  <h2 class="section-title" style="margin-bottom:16px">📸 Fotos e Vídeos do Projeto</h2>
  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:16px">
    <?php foreach ($arquivos as $index => $arq): ?>
    <div style="border:1px solid #e0e0e0;border-radius:8px;overflow:hidden;background:#fff;box-shadow:0 2px 4px rgba(0,0,0,.05)">
      <?php if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $arq['file_path'])): ?>
        <div onclick="abrirLightbox(<?php echo $index; ?>)" style="cursor:pointer;position:relative;overflow:hidden">
          <img src="<?php echo View::e($arq['file_path']); ?>" style="width:100%;height:200px;object-fit:cover;transition:transform .3s" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'"/>
          <div style="position:absolute;top:8px;right:8px;background:rgba(0,0,0,.6);color:#fff;padding:4px 8px;border-radius:4px;font-size:.7rem">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle">
              <polyline points="15 3 21 3 21 9"/>
              <polyline points="9 21 3 21 3 15"/>
              <line x1="21" y1="3" x2="14" y2="10"/>
              <line x1="3" y1="21" x2="10" y2="14"/>
            </svg>
          </div>
        </div>
      <?php elseif (preg_match('/\.(mp4|webm|mov)$/i', $arq['file_path'])): ?>
        <div onclick="abrirLightbox(<?php echo $index; ?>)" style="cursor:pointer;position:relative">
          <video src="<?php echo View::e($arq['file_path']); ?>" style="width:100%;height:200px;object-fit:cover"></video>
          <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background:rgba(0,0,0,.7);color:#fff;padding:12px;border-radius:50%;width:48px;height:48px;display:flex;align-items:center;justify-content:center">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
              <polygon points="5 3 19 12 5 21 5 3"/>
            </svg>
          </div>
        </div>
      <?php endif; ?>
      <div style="padding:12px">
        <div style="font-size:.8rem;color:#666;margin-bottom:6px;word-break:break-all">
          <?php echo View::e(basename($arq['file_path'])); ?>
        </div>
        <?php if (!empty($arq['caption'])): ?>
        <div style="font-size:.85rem;color:#333;background:#f9f9f9;padding:8px;border-radius:4px;border-left:3px solid var(--gold);line-height:1.5">
          <?php echo nl2br(View::e($arq['caption'])); ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Lightbox Modal -->
<div id="lightboxModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.95);z-index:9999;overflow:hidden">
  <button onclick="fecharLightbox()" style="position:absolute;top:20px;right:20px;background:rgba(255,255,255,.1);border:2px solid #fff;color:#fff;width:48px;height:48px;border-radius:50%;cursor:pointer;font-size:24px;display:flex;align-items:center;justify-content:center;z-index:10001;transition:all .2s">✕</button>
  <button onclick="navegarLightbox(-1)" style="position:absolute;left:20px;top:50%;transform:translateY(-50%);background:rgba(255,255,255,.1);border:2px solid #fff;color:#fff;width:56px;height:56px;border-radius:50%;cursor:pointer;font-size:28px;display:flex;align-items:center;justify-content:center;z-index:10001;transition:all .2s">‹</button>
  <button onclick="navegarLightbox(1)" style="position:absolute;right:20px;top:50%;transform:translateY(-50%);background:rgba(255,255,255,.1);border:2px solid #fff;color:#fff;width:56px;height:56px;border-radius:50%;cursor:pointer;font-size:28px;display:flex;align-items:center;justify-content:center;z-index:10001;transition:all .2s">›</button>
  <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);max-width:90%;max-height:90%;width:auto;height:auto;display:flex;flex-direction:column;align-items:center">
    <div id="lightboxContent" style="max-width:100%;max-height:calc(90vh - 100px);display:flex;align-items:center;justify-content:center"></div>
    <div id="lightboxCaption" style="color:#fff;margin-top:20px;text-align:center;max-width:800px;font-size:1rem;line-height:1.6;padding:0 20px"></div>
  </div>
  <div style="position:absolute;bottom:20px;left:50%;transform:translateX(-50%);color:#fff;font-size:.9rem;background:rgba(0,0,0,.5);padding:8px 16px;border-radius:20px">
    <span id="lightboxCounter"></span>
  </div>
</div>

<script>
const arquivosData = <?php echo json_encode(array_values($arquivos)); ?>;
let currentIndex = 0;

function abrirLightbox(index) {
  currentIndex = index;
  mostrarArquivo();
  document.getElementById('lightboxModal').style.display = 'block';
  document.body.style.overflow = 'hidden';
}

function fecharLightbox() {
  document.getElementById('lightboxModal').style.display = 'none';
  document.body.style.overflow = 'auto';
  const video = document.querySelector('#lightboxContent video');
  if (video) video.pause();
}

function navegarLightbox(direcao) {
  currentIndex += direcao;
  if (currentIndex < 0) currentIndex = arquivosData.length - 1;
  if (currentIndex >= arquivosData.length) currentIndex = 0;
  mostrarArquivo();
}

function mostrarArquivo() {
  const arquivo = arquivosData[currentIndex];
  const content = document.getElementById('lightboxContent');
  const caption = document.getElementById('lightboxCaption');
  const counter = document.getElementById('lightboxCounter');
  
  content.innerHTML = '';
  
  const isImage = /\.(jpg|jpeg|png|gif|webp)$/i.test(arquivo.file_path);
  const isVideo = /\.(mp4|webm|mov)$/i.test(arquivo.file_path);
  
  if (isImage) {
    const img = document.createElement('img');
    img.src = arquivo.file_path;
    img.style.cssText = 'max-width:100%;max-height:calc(90vh - 100px);object-fit:contain;border-radius:8px;box-shadow:0 8px 32px rgba(0,0,0,.5)';
    content.appendChild(img);
  } else if (isVideo) {
    const video = document.createElement('video');
    video.src = arquivo.file_path;
    video.controls = true;
    video.autoplay = true;
    video.style.cssText = 'max-width:100%;max-height:calc(90vh - 100px);border-radius:8px;box-shadow:0 8px 32px rgba(0,0,0,.5)';
    content.appendChild(video);
  }
  
  caption.textContent = arquivo.caption || '';
  counter.textContent = (currentIndex + 1) + ' / ' + arquivosData.length;
}

document.addEventListener('keydown', function(e) {
  const modal = document.getElementById('lightboxModal');
  if (modal.style.display === 'block') {
    if (e.key === 'Escape') fecharLightbox();
    if (e.key === 'ArrowLeft') navegarLightbox(-1);
    if (e.key === 'ArrowRight') navegarLightbox(1);
  }
});

document.getElementById('lightboxModal').addEventListener('click', function(e) {
  if (e.target === this) fecharLightbox();
});
</script>
<?php endif; ?>

<!-- Observações -->
<?php if (!empty($demanda['notes'])): ?>
<div class="card" style="margin-bottom:24px">
  <h3 class="card-title"><?php echo View::e(I18n::t('demanda.observacoes')); ?></h3>
  <p style="margin-top:12px;font-size:.88rem;line-height:1.6;white-space:pre-wrap"><?php echo View::e($demanda['notes']); ?></p>
</div>
<?php endif; ?>

<!-- Anexos -->
<?php if (!empty($demanda['arquivos'])): ?>
<div class="card" style="margin-bottom:24px">
  <h3 class="card-title"><?php echo View::e(I18n::t('demanda.uploads')); ?></h3>
  <div style="margin-top:12px;display:flex;flex-direction:column;gap:8px">
    <?php foreach ($demanda['arquivos'] as $arq): ?>
    <a href="/<?php echo View::e(ltrim($arq['file_path'], '/')); ?>" target="_blank"
       style="display:flex;align-items:center;gap:8px;font-size:.88rem;color:var(--gold);text-decoration:none">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      <?php echo View::e($arq['name']); ?>
      <?php if (!empty($arq['file_size'])): ?>
        <span style="color:var(--text-muted);font-size:.75rem">(<?php echo View::e(number_format($arq['file_size'] / 1024, 0, ',', '.')); ?> KB)</span>
      <?php endif; ?>
    </a>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<!-- Propostas apresentadas ao cliente -->
<?php if (!empty($propostas)): ?>
<div style="margin-bottom:24px">
  <h2 class="section-title" style="margin-bottom:16px"><?php echo View::e(I18n::t('sidebar_cli.propostas')); ?></h2>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Parceiro</th>
          <th>Valor</th>
          <th>Prazo</th>
          <th>Status</th>
          <th>Data</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($propostas as $p): ?>
          <?php if (!empty($p['presented_to_client'])): ?>
          <tr>
            <td><?php echo View::e($p['parceiro_nome'] ?? '—'); ?></td>
            <td>R$ <?php echo View::e(number_format((float)$p['amount'], 2, ',', '.')); ?></td>
            <td><?php echo $p['deadline_days'] ? View::e($p['deadline_days']) . ' dias' : '—'; ?></td>
            <td>
              <span class="badge <?php echo $statusBadge[$p['status']] ?? 'badge-gray'; ?>">
                <?php echo View::e(I18n::t('status_prop.' . $p['status']) ?: $p['status']); ?>
              </span>
            </td>
            <td><?php echo View::e(date('d/m/Y', strtotime($p['created_at']))); ?></td>
          </tr>
          <?php endif; ?>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>

<!-- Timeline -->
<?php if (!empty($timeline)): ?>
<div class="card">
  <h3 class="card-title">Timeline</h3>
  <div style="margin-top:16px">
    <?php foreach ($timeline as $event): ?>
    <div style="display:flex;gap:16px;padding:12px 0;border-bottom:1px solid var(--border)">
      <div style="flex-shrink:0;width:100px;font-size:.75rem;color:var(--text-muted)">
        <?php echo View::e(date('d/m/Y H:i', strtotime($event['created_at']))); ?>
      </div>
      <div style="font-size:.88rem">
        <strong style="font-size:.78rem;text-transform:uppercase;letter-spacing:.04em;color:var(--gold)"><?php echo View::e($event['event_type']); ?></strong>
        <?php if (!empty($event['description'])): ?>
          <p style="margin-top:4px;color:var(--text-muted)"><?php echo View::e($event['description']); ?></p>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>
