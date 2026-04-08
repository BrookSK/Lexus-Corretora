<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
$statusBadge = match($demanda['status'] ?? '') {
    'fechado_ganho' => 'badge-green',
    'fechado_perda', 'cancelado' => 'badge-red',
    'novo', 'em_triagem' => 'badge-blue',
    'pausado' => 'badge-gray',
    default => 'badge-gold',
};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e($demanda['code']); ?> — <?php echo View::e($demanda['title']); ?></h1>
    <p class="section-subtitle">
      <span class="badge <?php echo $statusBadge; ?>"><?php echo View::e($demanda['status']); ?></span>
      <?php if (!empty($demanda['cliente_id'])): ?>
        <span style="margin-left:8px;font-size:.85rem;color:#666">
          👤 Cliente: <strong><?php echo View::e($demanda['cliente_nome'] ?? 'ID: ' . $demanda['cliente_id']); ?></strong>
        </span>
      <?php endif; ?>
    </p>
  </div>
  <div style="display:flex;gap:8px">
    <button type="button" onclick="abrirModalCliente()" class="btn btn-secondary" style="display:flex;align-items:center;gap:6px">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
        <circle cx="12" cy="7" r="4"/>
      </svg>
      <?php echo !empty($demanda['cliente_id']) ? 'Ver/Alterar Cliente' : 'Atribuir Cliente'; ?>
    </button>
    <a href="/equipe/demandas/<?php echo (int)$demanda['id']; ?>/editar" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.editar')); ?></a>
    <a href="/equipe/distribuicao/<?php echo (int)$demanda['id']; ?>" class="btn btn-primary"><?php echo View::e(I18n::t('demandas.distribuir')); ?></a>
    <a href="/equipe/demandas" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
  </div>
</div>

<div class="cards-grid">
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('sidebar.clientes')); ?></div>
    <div class="card-title"><?php echo View::e($demanda['cliente_nome'] ?? '—'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('demandas.origem')); ?></div>
    <div class="card-title"><?php echo View::e($demanda['origin']); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('demandas.urgencia')); ?></div>
    <div class="card-title"><?php echo View::e($demanda['urgency'] ?? 'media'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('demandas.complexidade')); ?></div>
    <div class="card-title"><?php echo View::e($demanda['complexity'] ?? 'moderada'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('geral.cidade')); ?> / <?php echo View::e(I18n::t('geral.estado')); ?></div>
    <div class="card-title"><?php echo View::e(($demanda['city'] ?? '') . ' / ' . ($demanda['state'] ?? '')); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('demandas.area_m2')); ?></div>
    <div class="card-title"><?php echo View::e($demanda['area_sqm'] ?? '—'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('demandas.orcamento')); ?></div>
    <div class="card-title">
      <?php if (!empty($demanda['budget_min']) || !empty($demanda['budget_max'])): ?>
        <?php echo I18n::formatarMoeda($demanda['budget_min'] ?? 0); ?> — <?php echo I18n::formatarMoeda($demanda['budget_max'] ?? 0); ?>
      <?php else: ?>—<?php endif; ?>
    </div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('demandas.prazo_desejado')); ?></div>
    <div class="card-title"><?php echo View::e($demanda['desired_deadline'] ?? '—'); ?></div>
  </div>
</div>

<div class="card" style="margin-bottom:24px">
  <div class="card-label" style="margin-bottom:8px"><?php echo View::e(I18n::t('geral.descricao')); ?></div>
  <p style="font-size:.88rem;line-height:1.6"><?php echo nl2br(View::e($demanda['description'] ?? '')); ?></p>
</div>

<?php if (!empty($demanda['internal_notes'])): ?>
<div class="card" style="margin-bottom:24px;border-left:3px solid var(--gold)">
  <div class="card-label" style="margin-bottom:8px"><?php echo View::e(I18n::t('demandas.notas_internas')); ?></div>
  <p style="font-size:.88rem;line-height:1.6"><?php echo nl2br(View::e($demanda['internal_notes'])); ?></p>
</div>
<?php endif; ?>

<!-- Fotos e Vídeos do Projeto -->
<?php if (!empty($arquivos)): ?>
<div class="section-header" style="margin-top:32px">
  <div><h2 class="section-title">📸 Fotos e Vídeos do Projeto</h2></div>
</div>
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:16px;margin-bottom:32px">
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
    <?php else: ?>
      <a href="<?php echo View::e($arq['file_path']); ?>" target="_blank" style="display:block;height:200px;display:flex;align-items:center;justify-content:center;background:#f5f5f5;text-decoration:none">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2">
          <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/>
          <polyline points="13 2 13 9 20 9"/>
        </svg>
      </a>
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
      <div style="font-size:.7rem;color:#999;margin-top:8px">
        Enviado em <?php echo View::e(date('d/m/Y H:i', strtotime($arq['created_at']))); ?>
        <?php if (!empty($arq['uploaded_by_type'])): ?>
          por <?php echo View::e(ucfirst($arq['uploaded_by_type'])); ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Lightbox Modal -->
<div id="lightboxModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.95);z-index:9999;overflow:hidden">
  <button onclick="fecharLightbox()" style="position:absolute;top:20px;right:20px;background:rgba(255,255,255,.1);border:2px solid #fff;color:#fff;width:48px;height:48px;border-radius:50%;cursor:pointer;font-size:24px;display:flex;align-items:center;justify-content:center;z-index:10001;transition:all .2s" onmouseover="this.style.background='rgba(255,255,255,.2)'" onmouseout="this.style.background='rgba(255,255,255,.1)'">
    ✕
  </button>
  
  <button onclick="navegarLightbox(-1)" style="position:absolute;left:20px;top:50%;transform:translateY(-50%);background:rgba(255,255,255,.1);border:2px solid #fff;color:#fff;width:56px;height:56px;border-radius:50%;cursor:pointer;font-size:28px;display:flex;align-items:center;justify-content:center;z-index:10001;transition:all .2s" onmouseover="this.style.background='rgba(255,255,255,.2)'" onmouseout="this.style.background='rgba(255,255,255,.1)'">
    ‹
  </button>
  
  <button onclick="navegarLightbox(1)" style="position:absolute;right:20px;top:50%;transform:translateY(-50%);background:rgba(255,255,255,.1);border:2px solid #fff;color:#fff;width:56px;height:56px;border-radius:50%;cursor:pointer;font-size:28px;display:flex;align-items:center;justify-content:center;z-index:10001;transition:all .2s" onmouseover="this.style.background='rgba(255,255,255,.2)'" onmouseout="this.style.background='rgba(255,255,255,.1)'">
    ›
  </button>
  
  <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);max-width:90%;max-height:90%;width:auto;height:auto;display:flex;flex-direction:column;align-items:center">
    <div id="lightboxContent" style="max-width:100%;max-height:calc(90vh - 100px);display:flex;align-items:center;justify-content:center"></div>
    <div id="lightboxCaption" style="color:#fff;margin-top:20px;text-align:center;max-width:800px;font-size:1rem;line-height:1.6;padding:0 20px"></div>
    <div id="lightboxInfo" style="color:rgba(255,255,255,.7);margin-top:8px;font-size:.85rem"></div>
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
  
  // Pausar vídeo se estiver tocando
  const video = document.querySelector('#lightboxContent video');
  if (video) video.pause();
}

function navegarLightbox(direcao) {
  currentIndex += direcao;
  
  // Loop circular
  if (currentIndex < 0) currentIndex = arquivosData.length - 1;
  if (currentIndex >= arquivosData.length) currentIndex = 0;
  
  mostrarArquivo();
}

function mostrarArquivo() {
  const arquivo = arquivosData[currentIndex];
  const content = document.getElementById('lightboxContent');
  const caption = document.getElementById('lightboxCaption');
  const info = document.getElementById('lightboxInfo');
  const counter = document.getElementById('lightboxCounter');
  
  // Limpar conteúdo anterior
  content.innerHTML = '';
  
  // Verificar tipo de arquivo
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
  
  // Mostrar legenda
  caption.textContent = arquivo.caption || '';
  
  // Mostrar informações
  const data = new Date(arquivo.created_at);
  info.textContent = arquivo.name + ' • ' + data.toLocaleDateString('pt-BR') + ' ' + data.toLocaleTimeString('pt-BR', {hour: '2-digit', minute: '2-digit'});
  
  // Atualizar contador
  counter.textContent = (currentIndex + 1) + ' / ' + arquivosData.length;
}

// Navegação por teclado
document.addEventListener('keydown', function(e) {
  const modal = document.getElementById('lightboxModal');
  if (modal.style.display === 'block') {
    if (e.key === 'Escape') fecharLightbox();
    if (e.key === 'ArrowLeft') navegarLightbox(-1);
    if (e.key === 'ArrowRight') navegarLightbox(1);
  }
});

// Fechar ao clicar fora
document.getElementById('lightboxModal').addEventListener('click', function(e) {
  if (e.target === this) fecharLightbox();
});
</script>
<?php endif; ?>

<!-- Anexos (outros arquivos) -->
<?php if (!empty($demanda['arquivos'])): ?>
<div class="card" style="margin-bottom:24px">
  <div class="card-label" style="margin-bottom:12px"><?php echo View::e(I18n::t('demanda.uploads')); ?></div>
  <div style="display:flex;flex-direction:column;gap:8px">
    <?php foreach ($demanda['arquivos'] as $arq): ?>
    <a href="/<?php echo View::e(ltrim($arq['file_path'], '/')); ?>" target="_blank"
       style="display:flex;align-items:center;gap:8px;font-size:.85rem;color:var(--gold);text-decoration:none">
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

<!-- Distribuição -->
<?php if (!empty($distribuicoes)): ?>
<div class="section-header" style="margin-top:32px">
  <div><h2 class="section-title"><?php echo View::e(I18n::t('demandas.distribuicao')); ?></h2></div>
</div>
<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th><?php echo View::e(I18n::t('sidebar.parceiros')); ?></th>
        <th><?php echo View::e(I18n::t('geral.status')); ?></th>
        <th><?php echo View::e(I18n::t('geral.enviado_em')); ?></th>
        <th><?php echo View::e(I18n::t('geral.respondido_em')); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($distribuicoes as $dist): ?>
      <tr>
        <td><?php echo View::e($dist['parceiro_nome'] ?? '—'); ?></td>
        <td><span class="badge badge-gold"><?php echo View::e($dist['status']); ?></span></td>
        <td><?php echo View::e($dist['sent_at'] ?? '—'); ?></td>
        <td><?php echo View::e($dist['responded_at'] ?? '—'); ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>

<!-- Propostas -->
<div class="section-header" style="margin-top:32px">
  <div><h2 class="section-title"><?php echo View::e(I18n::t('sidebar.propostas')); ?></h2></div>
  <?php if (!empty($propostas) && count($propostas) > 1): ?>
  <a href="/equipe/propostas/comparar?demanda_id=<?php echo (int)$demanda['id']; ?>" class="btn btn-secondary btn-sm"><?php echo View::e(I18n::t('propostas.comparar')); ?></a>
  <?php endif; ?>
</div>
<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th><?php echo View::e(I18n::t('sidebar.parceiros')); ?></th>
        <th><?php echo View::e(I18n::t('propostas.valor')); ?></th>
        <th><?php echo View::e(I18n::t('geral.status')); ?></th>
        <th><?php echo View::e(I18n::t('geral.data')); ?></th>
        <th><?php echo View::e(I18n::t('geral.acoes')); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($propostas)): ?>
      <tr><td colspan="5"><?php echo View::e(I18n::t('geral.nenhum_registro')); ?></td></tr>
      <?php else: foreach ($propostas as $p): ?>
      <tr>
        <td><?php echo View::e($p['parceiro_nome'] ?? '—'); ?></td>
        <td><?php echo I18n::formatarMoeda($p['amount']); ?></td>
        <td><span class="badge badge-gold"><?php echo View::e($p['status']); ?></span></td>
        <td><?php echo View::e($p['created_at']); ?></td>
        <td><a href="/equipe/propostas/<?php echo (int)$p['id']; ?>" class="btn btn-secondary btn-sm"><?php echo View::e(I18n::t('geral.ver')); ?></a></td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>

<!-- Timeline -->
<div class="section-header" style="margin-top:32px">
  <div><h2 class="section-title"><?php echo View::e(I18n::t('geral.timeline')); ?></h2></div>
</div>
<div class="card">
  <?php if (empty($timeline)): ?>
    <p style="color:var(--text-muted);font-size:.88rem"><?php echo View::e(I18n::t('geral.nenhum_registro')); ?></p>
  <?php else: foreach ($timeline as $evento): ?>
    <div style="padding:12px 0;border-bottom:1px solid var(--border)">
      <span style="font-size:.72rem;color:var(--text-muted)"><?php echo View::e($evento['created_at']); ?> — <?php echo View::e($evento['actor_type'] ?? 'sistema'); ?></span>
      <p style="font-size:.88rem;margin-top:4px"><?php echo View::e($evento['description']); ?></p>
    </div>
  <?php endforeach; endif; ?>
</div>


<!-- Modal Atribuir Cliente -->
<div id="modalCliente" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.5);z-index:9999;overflow-y:auto">
  <div style="min-height:100%;display:flex;align-items:center;justify-content:center;padding:20px">
    <div style="background:#fff;border-radius:8px;max-width:600px;width:100%;max-height:90vh;overflow-y:auto;box-shadow:0 8px 32px rgba(0,0,0,.2)">
      <div style="padding:24px;border-bottom:1px solid #e0e0e0;display:flex;align-items:center;justify-content:space-between">
        <h3 style="margin:0;font-size:1.2rem;color:#333">
          <?php echo !empty($demanda['cliente_id']) ? 'Cliente Vinculado' : 'Atribuir Cliente à Demanda'; ?>
        </h3>
        <button onclick="fecharModalCliente()" style="background:none;border:none;font-size:24px;color:#999;cursor:pointer;padding:0;width:32px;height:32px;display:flex;align-items:center;justify-content:center">✕</button>
      </div>
      
      <div style="padding:24px">
        <?php if (!empty($demanda['cliente_id'])): ?>
        <!-- Cliente Atual -->
        <div style="background:#f9f9f9;padding:16px;border-radius:6px;border-left:3px solid var(--gold);margin-bottom:20px">
          <div style="font-size:.85rem;color:#666;margin-bottom:4px">Cliente Atual:</div>
          <div style="font-size:1.1rem;font-weight:600;color:#333;margin-bottom:8px">
            <?php echo View::e($demanda['cliente_nome'] ?? 'Cliente ID: ' . $demanda['cliente_id']); ?>
          </div>
          <?php if (!empty($demanda['cliente_email'])): ?>
          <div style="font-size:.85rem;color:#666">
            📧 <?php echo View::e($demanda['cliente_email']); ?>
          </div>
          <?php endif; ?>
          <?php if (!empty($demanda['cliente_phone'])): ?>
          <div style="font-size:.85rem;color:#666;margin-top:4px">
            📱 <?php echo View::e($demanda['cliente_phone']); ?>
          </div>
          <?php endif; ?>
          <a href="/equipe/clientes/<?php echo (int)$demanda['cliente_id']; ?>" target="_blank" style="display:inline-block;margin-top:12px;color:var(--gold);font-size:.85rem;text-decoration:none">
            Ver perfil completo →
          </a>
        </div>
        
        <div style="margin-bottom:16px">
          <strong style="font-size:.9rem;color:#333">Alterar para outro cliente:</strong>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="/equipe/demandas/<?php echo (int)$demanda['id']; ?>/atribuir-cliente">
          <?php echo Csrf::campo(); ?>
          
          <div style="margin-bottom:16px">
            <label style="display:block;margin-bottom:8px;font-size:.9rem;color:#333">
              Buscar Cliente
            </label>
            <input type="text" id="buscaCliente" placeholder="Digite nome, e-mail ou telefone..." style="width:100%;padding:12px;border:1px solid #ddd;border-radius:4px;font-size:.9rem;box-sizing:border-box" oninput="buscarClientes(this.value)"/>
          </div>
          
          <div id="listaClientes" style="max-height:300px;overflow-y:auto;border:1px solid #e0e0e0;border-radius:4px;margin-bottom:16px">
            <div style="padding:40px;text-align:center;color:#999;font-size:.9rem">
              Digite para buscar clientes...
            </div>
          </div>
          
          <input type="hidden" name="cliente_id" id="clienteSelecionadoId" value="<?php echo (int)($demanda['cliente_id'] ?? 0); ?>"/>
          
          <div style="display:flex;gap:12px;justify-content:flex-end">
            <button type="button" onclick="fecharModalCliente()" class="btn btn-secondary">
              Cancelar
            </button>
            <button type="submit" class="btn btn-primary" id="btnSalvarCliente" disabled>
              Salvar Vinculação
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
let clientesBusca = [];
let clienteSelecionado = <?php echo (int)($demanda['cliente_id'] ?? 0); ?>;

function abrirModalCliente() {
  document.getElementById('modalCliente').style.display = 'block';
  document.body.style.overflow = 'hidden';
  
  // Carregar clientes automaticamente
  buscarClientes('');
}

function fecharModalCliente() {
  document.getElementById('modalCliente').style.display = 'none';
  document.body.style.overflow = 'auto';
}

let timeoutBusca = null;
function buscarClientes(termo) {
  clearTimeout(timeoutBusca);
  
  const lista = document.getElementById('listaClientes');
  
  // Mostrar loading
  lista.innerHTML = '<div style="padding:20px;text-align:center;color:#666;font-size:.85rem">🔍 Buscando...</div>';
  
  timeoutBusca = setTimeout(() => {
    fetch('/equipe/clientes/buscar?q=' + encodeURIComponent(termo))
      .then(r => {
        console.log('Status da resposta:', r.status);
        if (!r.ok) throw new Error('Erro na requisição: ' + r.status);
        return r.json();
      })
      .then(data => {
        console.log('Clientes encontrados:', data);
        clientesBusca = data;
        renderizarClientes();
      })
      .catch(err => {
        console.error('Erro ao buscar clientes:', err);
        lista.innerHTML = '<div style="padding:20px;text-align:center;color:#dc3545;font-size:.85rem">❌ Erro ao buscar clientes. Tente novamente.<br><small style="color:#999;margin-top:8px;display:block">' + err.message + '</small></div>';
      });
  }, 300);
}

function renderizarClientes() {
  const lista = document.getElementById('listaClientes');
  
  console.log('Renderizando clientes:', clientesBusca);
  
  if (!clientesBusca || clientesBusca.length === 0) {
    lista.innerHTML = '<div style="padding:20px;text-align:center;color:#999;font-size:.85rem">Nenhum cliente encontrado</div>';
    return;
  }
  
  lista.innerHTML = clientesBusca.map(cliente => {
    const selecionado = parseInt(cliente.id) === parseInt(clienteSelecionado);
    return `
      <div onclick="selecionarCliente(${cliente.id})" style="padding:12px;border-bottom:1px solid #f0f0f0;cursor:pointer;transition:background .2s;${selecionado ? 'background:#f0f8ff;border-left:3px solid var(--gold)' : ''}" onmouseover="if(!${selecionado})this.style.background='#f9f9f9'" onmouseout="if(!${selecionado})this.style.background='#fff'">
        <div style="display:flex;align-items:center;justify-content:space-between">
          <div>
            <div style="font-weight:600;color:#333;margin-bottom:4px">${cliente.name || 'Sem nome'}</div>
            <div style="font-size:.8rem;color:#666">
              ${cliente.email || ''}
              ${cliente.phone ? ' • ' + cliente.phone : ''}
            </div>
          </div>
          ${selecionado ? '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>' : ''}
        </div>
      </div>
    `;
  }).join('');
}

function selecionarCliente(id) {
  clienteSelecionado = id;
  document.getElementById('clienteSelecionadoId').value = id;
  document.getElementById('btnSalvarCliente').disabled = false;
  renderizarClientes();
}

// Fechar modal ao clicar fora
document.getElementById('modalCliente').addEventListener('click', function(e) {
  if (e.target === this) fecharModalCliente();
});
</script>
