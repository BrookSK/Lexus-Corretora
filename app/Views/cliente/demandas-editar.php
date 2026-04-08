<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};

/**
 * Edição de demanda — painel do cliente
 * Variáveis: $demanda (array), $arquivos (array)
 */
?>
<div class="section-header">
  <div>
    <h1 class="section-title">Editar Demanda</h1>
    <p class="section-subtitle"><?php echo View::e($demanda['code']); ?></p>
  </div>
  <a href="/cliente/demandas/<?php echo (int)$demanda['id']; ?>" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<div class="card">
  <form method="POST" action="/cliente/demandas/<?php echo (int)$demanda['id']; ?>/editar" enctype="multipart/form-data">
    <?php echo Csrf::campo(); ?>

    <div class="form-grid">
      <div class="form-group">
        <label for="title">Título da Demanda *</label>
        <input type="text" id="title" name="title" class="form-control" value="<?php echo View::e($demanda['title'] ?? ''); ?>" required>
      </div>

      <div class="form-group">
        <label for="category">Categoria *</label>
        <select id="category" name="category" class="form-control" required>
          <option value="">Selecione...</option>
          <option value="residencial" <?php echo ($demanda['category'] ?? '') === 'residencial' ? 'selected' : ''; ?>>Residencial</option>
          <option value="comercial" <?php echo ($demanda['category'] ?? '') === 'comercial' ? 'selected' : ''; ?>>Comercial</option>
          <option value="industrial" <?php echo ($demanda['category'] ?? '') === 'industrial' ? 'selected' : ''; ?>>Industrial</option>
          <option value="reforma" <?php echo ($demanda['category'] ?? '') === 'reforma' ? 'selected' : ''; ?>>Reforma</option>
          <option value="outro" <?php echo ($demanda['category'] ?? '') === 'outro' ? 'selected' : ''; ?>>Outro</option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="description">Descrição do Projeto *</label>
      <textarea id="description" name="description" class="form-control" rows="6" required><?php echo View::e($demanda['description'] ?? ''); ?></textarea>
    </div>

    <div class="form-grid">
      <div class="form-group">
        <label for="work_type">Tipo de Obra</label>
        <input type="text" id="work_type" name="work_type" class="form-control" value="<?php echo View::e($demanda['work_type'] ?? ''); ?>">
      </div>

      <div class="form-group">
        <label for="current_phase">Fase Atual</label>
        <select id="current_phase" name="current_phase" class="form-control">
          <option value="">Selecione...</option>
          <option value="planejamento" <?php echo ($demanda['current_phase'] ?? '') === 'planejamento' ? 'selected' : ''; ?>>Planejamento</option>
          <option value="projeto" <?php echo ($demanda['current_phase'] ?? '') === 'projeto' ? 'selected' : ''; ?>>Projeto</option>
          <option value="aprovacao" <?php echo ($demanda['current_phase'] ?? '') === 'aprovacao' ? 'selected' : ''; ?>>Aprovação</option>
          <option value="execucao" <?php echo ($demanda['current_phase'] ?? '') === 'execucao' ? 'selected' : ''; ?>>Execução</option>
        </select>
      </div>
    </div>

    <div class="form-grid">
      <div class="form-group">
        <label for="city">Cidade *</label>
        <input type="text" id="city" name="city" class="form-control" value="<?php echo View::e($demanda['city'] ?? ''); ?>" required>
      </div>

      <div class="form-group">
        <label for="state">Estado *</label>
        <input type="text" id="state" name="state" class="form-control" value="<?php echo View::e($demanda['state'] ?? ''); ?>" required>
      </div>
    </div>

    <div class="form-group">
      <label for="address">Endereço Completo</label>
      <input type="text" id="address" name="address" class="form-control" value="<?php echo View::e($demanda['address'] ?? ''); ?>">
    </div>

    <div class="form-grid">
      <div class="form-group">
        <label for="area_sqm">Metragem (m²)</label>
        <input type="number" id="area_sqm" name="area_sqm" class="form-control" step="0.01" value="<?php echo View::e($demanda['area_sqm'] ?? ''); ?>">
      </div>

      <div class="form-group">
        <label for="desired_deadline">Prazo Desejado</label>
        <input type="date" id="desired_deadline" name="desired_deadline" class="form-control" value="<?php echo View::e($demanda['desired_deadline'] ?? ''); ?>">
      </div>
    </div>

    <div class="form-grid">
      <div class="form-group">
        <label for="budget_min">Orçamento Mínimo (R$)</label>
        <input type="number" id="budget_min" name="budget_min" class="form-control" step="0.01" value="<?php echo View::e($demanda['budget_min'] ?? ''); ?>">
      </div>

      <div class="form-group">
        <label for="budget_max">Orçamento Máximo (R$)</label>
        <input type="number" id="budget_max" name="budget_max" class="form-control" step="0.01" value="<?php echo View::e($demanda['budget_max'] ?? ''); ?>">
      </div>
    </div>

    <div class="form-grid" style="grid-template-columns:repeat(3,1fr)">
      <div class="form-group">
        <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
          <input type="checkbox" name="has_project" value="1" <?php echo !empty($demanda['has_project']) ? 'checked' : ''; ?>>
          <span>Possui Projeto</span>
        </label>
      </div>

      <div class="form-group">
        <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
          <input type="checkbox" name="has_architect" value="1" <?php echo !empty($demanda['has_architect']) ? 'checked' : ''; ?>>
          <span>Possui Arquiteto</span>
        </label>
      </div>

      <div class="form-group">
        <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
          <input type="checkbox" name="wants_multiple_proposals" value="1" <?php echo !empty($demanda['wants_multiple_proposals']) ? 'checked' : ''; ?>>
          <span>Aceita Múltiplas Propostas</span>
        </label>
      </div>
    </div>

    <div class="form-group">
      <label for="notes">Observações Adicionais</label>
      <textarea id="notes" name="notes" class="form-control" rows="4"><?php echo View::e($demanda['notes'] ?? ''); ?></textarea>
    </div>

    <!-- Upload de Fotos/Vídeos -->
    <div class="form-group">
      <label>Fotos e Vídeos do Projeto</label>
      <input type="file" id="fileInput" name="files[]" multiple accept="image/*,video/*" style="display:none">
      <button type="button" onclick="document.getElementById('fileInput').click()" class="btn btn-secondary">
        📷 Adicionar Fotos/Vídeos
      </button>
      <div id="filePreview" style="margin-top:16px;display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px"></div>
    </div>

    <!-- Arquivos Existentes -->
    <?php if (!empty($arquivos)): ?>
    <div style="margin-top:24px">
      <h3 style="font-size:1rem;margin-bottom:12px">Arquivos Atuais</h3>
      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:12px">
        <?php foreach ($arquivos as $arq): ?>
        <div style="border:1px solid #e0e0e0;border-radius:8px;padding:12px;background:#f9f9f9">
          <?php if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $arq['file_path'])): ?>
            <img src="<?php echo View::e($arq['file_path']); ?>" style="width:100%;height:120px;object-fit:cover;border-radius:4px;margin-bottom:8px">
          <?php elseif (preg_match('/\.(mp4|webm|mov)$/i', $arq['file_path'])): ?>
            <video src="<?php echo View::e($arq['file_path']); ?>" style="width:100%;height:120px;object-fit:cover;border-radius:4px;margin-bottom:8px"></video>
          <?php endif; ?>
          <div style="font-size:.8rem;color:#666;word-break:break-all"><?php echo View::e(basename($arq['file_path'])); ?></div>
          <?php if (!empty($arq['caption'])): ?>
          <div style="font-size:.75rem;color:#999;margin-top:4px"><?php echo View::e($arq['caption']); ?></div>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <div style="margin-top:32px;padding-top:24px;border-top:1px solid var(--border);display:flex;gap:12px">
      <button type="submit" class="btn btn-primary">Salvar Alterações</button>
      <a href="/cliente/demandas/<?php echo (int)$demanda['id']; ?>" class="btn btn-secondary">Cancelar</a>
    </div>
  </form>
</div>

<script>
const fileInput = document.getElementById('fileInput');
const filePreview = document.getElementById('filePreview');
let selectedFiles = [];

fileInput.addEventListener('change', function(e) {
  const files = Array.from(e.target.files);
  files.forEach((file, index) => {
    selectedFiles.push(file);
    const reader = new FileReader();
    reader.onload = function(e) {
      const preview = document.createElement('div');
      preview.style.cssText = 'border:1px solid #e0e0e0;border-radius:8px;overflow:hidden;background:#fff';
      
      const isImage = file.type.startsWith('image/');
      const isVideo = file.type.startsWith('video/');
      
      let mediaHtml = '';
      if (isImage) {
        mediaHtml = `<img src="${e.target.result}" style="width:100%;height:150px;object-fit:cover">`;
      } else if (isVideo) {
        mediaHtml = `<video src="${e.target.result}" style="width:100%;height:150px;object-fit:cover"></video>`;
      }
      
      preview.innerHTML = `
        ${mediaHtml}
        <div style="padding:12px">
          <div style="font-size:.8rem;color:#666;margin-bottom:8px;word-break:break-all">${file.name}</div>
          <div style="font-size:.75rem;color:#999;margin-bottom:8px">${(file.size / 1024).toFixed(0)} KB</div>
          <input type="text" name="captions[]" placeholder="Legenda (opcional)" class="form-control" style="font-size:.85rem;padding:6px">
          <button type="button" onclick="this.closest('div').parentElement.remove()" class="btn btn-secondary" style="margin-top:8px;font-size:.8rem;padding:4px 8px">Remover</button>
        </div>
      `;
      filePreview.appendChild(preview);
    };
    reader.readAsDataURL(file);
  });
});
</script>
