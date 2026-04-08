<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('demandas.editar_demanda')); ?></h1>
    <p class="section-subtitle"><?php echo View::e($demanda['code']); ?> — <?php echo View::e($demanda['title']); ?></p>
  </div>
  <a href="/equipe/demandas/<?php echo (int)$demanda['id']; ?>" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<div class="card">
  <form method="POST" action="/equipe/demandas/<?php echo (int)$demanda['id']; ?>/editar" enctype="multipart/form-data">
    <?php echo Csrf::campo(); ?>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.titulo')); ?> *</label>
        <input type="text" name="title" value="<?php echo View::e($demanda['title']); ?>" required/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.status')); ?></label>
        <select name="status">
          <?php foreach (['novo','em_triagem','em_estruturacao','pronto_repasse','distribuido','aguardando_respostas','recebendo_propostas','em_curadoria','apresentado_cliente','em_negociacao','contrato_formalizacao','fechado_ganho','fechado_perda','pausado','cancelado'] as $s): ?>
          <option value="<?php echo $s; ?>" <?php echo ($demanda['status'] ?? '') === $s ? 'selected' : ''; ?>><?php echo ucfirst(str_replace('_', ' ', $s)); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demandas.origem')); ?></label>
        <select name="origin">
          <?php foreach (['cliente','parceiro','arquiteto','equipe','lead','importacao'] as $o): ?>
          <option value="<?php echo $o; ?>" <?php echo ($demanda['origin'] ?? '') === $o ? 'selected' : ''; ?>><?php echo ucfirst($o); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('sidebar.clientes')); ?></label>
        <select name="cliente_id">
          <option value="">— <?php echo View::e(I18n::t('geral.selecione')); ?> —</option>
          <?php if (!empty($clientes)): foreach ($clientes as $c): ?>
          <option value="<?php echo (int)$c['id']; ?>" <?php echo ((int)($demanda['cliente_id'] ?? 0)) === (int)$c['id'] ? 'selected' : ''; ?>><?php echo View::e($c['name']); ?></option>
          <?php endforeach; endif; ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label><?php echo View::e(I18n::t('geral.descricao')); ?> *</label>
      <textarea name="description" required rows="4"><?php echo View::e($demanda['description'] ?? ''); ?></textarea>
    </div>

    <div class="form-row">
      <div class="form-group">
        <?php require __DIR__ . '/../_partials/categorias.php'; ?>
        <label><?php echo View::e(I18n::t('demandas.categoria')); ?></label>
        <select name="category">
          <option value="">— Selecione —</option>
          <?php foreach ($CATEGORIAS_NICHO as $cat): ?>
          <option value="<?php echo View::e($cat); ?>" <?php echo ($demanda['category'] ?? '') === $cat ? 'selected' : ''; ?>><?php echo View::e($cat); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demandas.tipo_obra')); ?></label>
        <input type="text" name="work_type" value="<?php echo View::e($demanda['work_type'] ?? ''); ?>"/>
      </div>
    </div>

    <div class="form-row">
      <?php
      $estadoSelecionado = $demanda['state'] ?? '';
      $cidadeSelecionada = $demanda['city'] ?? '';
      $obrigatorio = false;
      include __DIR__ . '/../_partials/campos-estado-cidade.php';
      ?>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demandas.orcamento_min')); ?></label>
        <input type="number" name="budget_min" step="0.01" value="<?php echo View::e($demanda['budget_min'] ?? ''); ?>"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demandas.orcamento_max')); ?></label>
        <input type="number" name="budget_max" step="0.01" value="<?php echo View::e($demanda['budget_max'] ?? ''); ?>"/>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demandas.urgencia')); ?></label>
        <select name="urgency">
          <?php foreach (['baixa','media','alta','critica'] as $u): ?>
          <option value="<?php echo $u; ?>" <?php echo ($demanda['urgency'] ?? '') === $u ? 'selected' : ''; ?>><?php echo ucfirst($u); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demandas.complexidade')); ?></label>
        <select name="complexity">
          <?php foreach (['simples','moderada','complexa','muito_complexa'] as $cx): ?>
          <option value="<?php echo $cx; ?>" <?php echo ($demanda['complexity'] ?? '') === $cx ? 'selected' : ''; ?>><?php echo ucfirst(str_replace('_', ' ', $cx)); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demandas.prazo_desejado')); ?></label>
        <input type="date" name="desired_deadline" value="<?php echo View::e($demanda['desired_deadline'] ?? ''); ?>"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demandas.prioridade')); ?></label>
        <select name="priority">
          <?php foreach (['baixa','normal','alta','urgente'] as $pr): ?>
          <option value="<?php echo $pr; ?>" <?php echo ($demanda['priority'] ?? '') === $pr ? 'selected' : ''; ?>><?php echo ucfirst($pr); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label><?php echo View::e(I18n::t('demandas.notas_internas')); ?></label>
      <textarea name="internal_notes" rows="3"><?php echo View::e($demanda['internal_notes'] ?? ''); ?></textarea>
    </div>

    <!-- UPLOAD DE ARQUIVOS COM LEGENDAS -->
    <h3 style="font-size:.9rem;margin:32px 0 16px;color:var(--gold)">📸 Fotos e Vídeos do Projeto</h3>
    
    <?php if (!empty($arquivos)): ?>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px;margin-bottom:24px">
      <?php foreach ($arquivos as $arq): ?>
      <div class="arquivo-item" data-id="<?php echo (int)$arq['id']; ?>" style="border:1px solid #e0e0e0;border-radius:6px;overflow:hidden;background:#fafafa">
        <?php if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $arq['file_path'])): ?>
          <img src="<?php echo View::e($arq['file_path']); ?>" style="width:100%;height:150px;object-fit:cover"/>
        <?php elseif (preg_match('/\.(mp4|webm|mov)$/i', $arq['file_path'])): ?>
          <video src="<?php echo View::e($arq['file_path']); ?>" style="width:100%;height:150px;object-fit:cover" controls></video>
        <?php else: ?>
          <div style="height:150px;display:flex;align-items:center;justify-content:center;background:#f0f0f0">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2">
              <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/>
              <polyline points="13 2 13 9 20 9"/>
            </svg>
          </div>
        <?php endif; ?>
        <div style="padding:12px">
          <div style="font-size:.8rem;color:#666;margin-bottom:6px"><?php echo View::e(basename($arq['file_path'])); ?></div>
          <?php if (!empty($arq['caption'])): ?>
          <div style="font-size:.75rem;color:#333;background:#fff;padding:6px;border-radius:3px;margin-bottom:8px"><?php echo View::e($arq['caption']); ?></div>
          <?php endif; ?>
          <button type="button" onclick="removerArquivo(<?php echo (int)$arq['id']; ?>)" style="width:100%;padding:6px;background:#dc3545;color:#fff;border:none;border-radius:3px;cursor:pointer;font-size:.75rem">Remover</button>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- NOVO UPLOAD MÚLTIPLO COM PREVIEW E LEGENDAS -->
    <div style="background:#f9f9f9;padding:20px;border-radius:8px;margin-bottom:24px">
      <div style="margin-bottom:16px">
        <input type="file" id="multipleFilesInput" multiple accept="image/*,video/*" style="display:none"/>
        <button type="button" onclick="document.getElementById('multipleFilesInput').click()" style="width:100%;padding:16px;border:2px dashed #b8945a;background:#fff;color:#b8945a;font-size:.9rem;cursor:pointer;border-radius:6px;display:flex;align-items:center;justify-content:center;gap:8px;transition:all .2s">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
            <circle cx="8.5" cy="8.5" r="1.5"/>
            <polyline points="21 15 16 10 5 21"/>
          </svg>
          <span style="font-weight:600">Selecionar Múltiplas Fotos/Vídeos</span>
        </button>
        <small style="display:block;margin-top:8px;color:#666;font-size:.8rem">
          💡 Você pode selecionar várias fotos de uma vez. Depois adicione legendas para cada uma.
        </small>
      </div>

      <!-- PREVIEW COM LEGENDAS -->
      <div id="filesPreviewContainer" style="display:none">
        <div style="background:#fff;padding:16px;border-radius:6px;border:1px solid #e0e0e0">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
            <strong style="color:#333;font-size:.9rem">
              <span id="selectedCount">0</span> arquivo(s) selecionado(s)
            </strong>
            <button type="button" onclick="limparSelecao()" style="padding:6px 12px;background:#dc3545;color:#fff;border:none;border-radius:4px;cursor:pointer;font-size:.8rem">
              Limpar Seleção
            </button>
          </div>
          <div id="filesPreviewGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:16px"></div>
        </div>
      </div>
    </div>

    <div style="margin-top:24px">
      <button type="submit" class="btn btn-primary"><?php echo View::e(I18n::t('geral.salvar')); ?></button>
    </div>
  </form>
</div>

<script>
let selectedFiles = [];

document.getElementById('multipleFilesInput').addEventListener('change', function(e) {
  const files = Array.from(e.target.files);
  if (files.length === 0) return;
  
  selectedFiles = files;
  renderFilesPreview();
});

function renderFilesPreview() {
  const container = document.getElementById('filesPreviewContainer');
  const grid = document.getElementById('filesPreviewGrid');
  const count = document.getElementById('selectedCount');
  
  if (selectedFiles.length === 0) {
    container.style.display = 'none';
    return;
  }
  
  container.style.display = 'block';
  count.textContent = selectedFiles.length;
  grid.innerHTML = '';
  
  selectedFiles.forEach((file, index) => {
    const item = document.createElement('div');
    item.style.cssText = 'border:1px solid #e0e0e0;border-radius:6px;overflow:hidden;background:#fafafa';
    
    // Preview da imagem/vídeo
    let preview = '';
    if (file.type.startsWith('image/')) {
      const url = URL.createObjectURL(file);
      preview = `<img src="${url}" style="width:100%;height:150px;object-fit:cover"/>`;
    } else if (file.type.startsWith('video/')) {
      const url = URL.createObjectURL(file);
      preview = `<video src="${url}" style="width:100%;height:150px;object-fit:cover" controls></video>`;
    } else {
      preview = `<div style="height:150px;display:flex;align-items:center;justify-content:center;background:#f0f0f0">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2">
          <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/>
          <polyline points="13 2 13 9 20 9"/>
        </svg>
      </div>`;
    }
    
    item.innerHTML = `
      ${preview}
      <div style="padding:12px">
        <div style="font-size:.75rem;color:#666;margin-bottom:8px;word-break:break-all">
          ${file.name}
        </div>
        <div style="font-size:.7rem;color:#999;margin-bottom:8px">
          ${(file.size / 1024 / 1024).toFixed(2)} MB
        </div>
        <textarea 
          id="caption_${index}" 
          placeholder="Adicione uma legenda (opcional)..." 
          rows="2" 
          style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;font-size:.8rem;resize:vertical;box-sizing:border-box"
        ></textarea>
        <button type="button" onclick="removerArquivoSelecionado(${index})" style="width:100%;margin-top:8px;padding:6px;background:#dc3545;color:#fff;border:none;border-radius:3px;cursor:pointer;font-size:.75rem">
          Remover
        </button>
      </div>
    `;
    
    grid.appendChild(item);
  });
}

function removerArquivoSelecionado(index) {
  selectedFiles.splice(index, 1);
  renderFilesPreview();
  
  // Atualizar input file
  const input = document.getElementById('multipleFilesInput');
  const dt = new DataTransfer();
  selectedFiles.forEach(file => dt.items.add(file));
  input.files = dt.files;
}

function limparSelecao() {
  selectedFiles = [];
  document.getElementById('multipleFilesInput').value = '';
  renderFilesPreview();
}

// Antes de submeter o form, criar inputs hidden com as legendas
document.querySelector('form').addEventListener('submit', function(e) {
  // Remover inputs antigos se existirem
  document.querySelectorAll('.dynamic-caption-input').forEach(el => el.remove());
  
  // Criar DataTransfer para os arquivos
  const dt = new DataTransfer();
  
  selectedFiles.forEach((file, index) => {
    dt.items.add(file);
    
    // Pegar a legenda do textarea
    const captionTextarea = document.getElementById(`caption_${index}`);
    const caption = captionTextarea ? captionTextarea.value : '';
    
    // Criar input hidden para a legenda
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'new_captions[]';
    input.value = caption;
    input.className = 'dynamic-caption-input';
    this.appendChild(input);
  });
  
  // Atualizar o input file com os arquivos na ordem correta
  const fileInput = document.getElementById('multipleFilesInput');
  fileInput.files = dt.files;
  
  // Criar input file real para envio
  const realFileInput = document.createElement('input');
  realFileInput.type = 'file';
  realFileInput.name = 'new_files[]';
  realFileInput.multiple = true;
  realFileInput.style.display = 'none';
  realFileInput.files = dt.files;
  realFileInput.className = 'dynamic-caption-input';
  this.appendChild(realFileInput);
});

function removerArquivo(id) {
  if (!confirm('Tem certeza que deseja remover este arquivo?')) return;
  
  fetch('/equipe/demandas/arquivo/' + id + '/remover', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-Token': document.querySelector('[name="_csrf_token"]').value
    }
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      document.querySelector('[data-id="' + id + '"]').remove();
    } else {
      alert('Erro ao remover arquivo');
    }
  })
  .catch(() => alert('Erro ao remover arquivo'));
}
</script>
