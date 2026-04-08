<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};

/**
 * Perfil profissional do parceiro
 * Variáveis: $parceiro (array)
 */

require __DIR__ . '/../_partials/categorias.php';
$especialidadesDisponiveis = $CATEGORIAS_NICHO;

$especialidadesSelecionadas = [];
if (!empty($parceiro['specialties'])) {
    $raw = $parceiro['specialties'];
    if (is_string($raw)) {
        $decoded = json_decode($raw, true);
        $especialidadesSelecionadas = is_array($decoded) ? $decoded : array_map('trim', explode(',', $raw));
    } elseif (is_array($raw)) {
        $especialidadesSelecionadas = $raw;
    }
}

$estadosBrasileiros = [
    'AC' => 'Acre', 'AL' => 'Alagoas', 'AP' => 'Amapá', 'AM' => 'Amazonas',
    'BA' => 'Bahia', 'CE' => 'Ceará', 'DF' => 'Distrito Federal', 'ES' => 'Espírito Santo',
    'GO' => 'Goiás', 'MA' => 'Maranhão', 'MT' => 'Mato Grosso', 'MS' => 'Mato Grosso do Sul',
    'MG' => 'Minas Gerais', 'PA' => 'Pará', 'PB' => 'Paraíba', 'PR' => 'Paraná',
    'PE' => 'Pernambuco', 'PI' => 'Piauí', 'RJ' => 'Rio de Janeiro', 'RN' => 'Rio Grande do Norte',
    'RS' => 'Rio Grande do Sul', 'RO' => 'Rondônia', 'RR' => 'Roraima', 'SC' => 'Santa Catarina',
    'SP' => 'São Paulo', 'SE' => 'Sergipe', 'TO' => 'Tocantins',
];

$cidadesSelecionadas = [];
if (!empty($parceiro['service_cities'])) {
    $raw = $parceiro['service_cities'];
    if (is_string($raw)) {
        $decoded = json_decode($raw, true);
        $cidadesSelecionadas = is_array($decoded) ? $decoded : array_map('trim', explode(',', $raw));
    } elseif (is_array($raw)) {
        $cidadesSelecionadas = $raw;
    }
}

$estadosSelecionados = [];
if (!empty($parceiro['service_states'])) {
    $raw = $parceiro['service_states'];
    if (is_string($raw)) {
        $decoded = json_decode($raw, true);
        $estadosSelecionados = is_array($decoded) ? $decoded : array_map('trim', explode(',', $raw));
    } elseif (is_array($raw)) {
        $estadosSelecionados = $raw;
    }
}
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar_par.perfil')); ?></h1>
    <p class="section-subtitle">Informações da empresa e perfil profissional</p>
  </div>
</div>

<form method="POST" action="/parceiro/perfil" enctype="multipart/form-data">
  <?php echo Csrf::campo(); ?>

  <!-- Dados da Empresa -->
  <div class="card" style="margin-bottom:24px;padding:32px">
    <h2 class="card-title" style="margin-bottom:20px"><?php echo View::e(I18n::t('parceiro.dados_empresa')); ?></h2>

    <div class="form-row">
      <div class="form-group">
        <label>Nome da Empresa / Profissional *</label>
        <input type="text" name="name" value="<?php echo View::e($parceiro['name'] ?? ''); ?>" required/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('parceiro.tipo')); ?> *</label>
        <select name="type" required>
          <option value="">— Selecione —</option>
          <option value="construtora" <?php echo ($parceiro['type'] ?? '') === 'construtora' ? 'selected' : ''; ?>>Construtora</option>
          <option value="arquiteto" <?php echo ($parceiro['type'] ?? '') === 'arquiteto' ? 'selected' : ''; ?>>Arquiteto</option>
          <option value="engenheiro" <?php echo ($parceiro['type'] ?? '') === 'engenheiro' ? 'selected' : ''; ?>>Engenheiro</option>
          <option value="empreiteira" <?php echo ($parceiro['type'] ?? '') === 'empreiteira' ? 'selected' : ''; ?>>Empreiteira</option>
          <option value="prestador" <?php echo ($parceiro['type'] ?? '') === 'prestador' ? 'selected' : ''; ?>>Prestador</option>
          <option value="fornecedor" <?php echo ($parceiro['type'] ?? '') === 'fornecedor' ? 'selected' : ''; ?>>Fornecedor</option>
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>CNPJ / CPF *</label>
        <input type="text" name="document" value="<?php echo View::e($parceiro['document'] ?? ''); ?>" required/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('auth.email')); ?></label>
        <input type="email" name="email" value="<?php echo View::e($parceiro['email'] ?? ''); ?>" readonly style="background:var(--bg-surface);cursor:not-allowed"/>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('contato.telefone')); ?> *</label>
        <input type="tel" name="phone" value="<?php echo View::e($parceiro['phone'] ?? ''); ?>" required/>
      </div>
      <div class="form-group">
        <label>WhatsApp *</label>
        <input type="tel" name="whatsapp" value="<?php echo View::e($parceiro['whatsapp'] ?? ''); ?>" required/>
      </div>
    </div>
  </div>

  <!-- Dados Profissionais -->
  <div class="card" style="margin-bottom:24px;padding:32px">
    <h2 class="card-title" style="margin-bottom:20px"><?php echo View::e(I18n::t('parceiro.dados_prof')); ?></h2>

    <div class="form-group">
      <label><?php echo View::e(I18n::t('parceiro.especialidades')); ?> *</label>
      <?php $mcId = 'mc-especialidades'; ?>
      <?php $mcSel = $especialidadesSelecionadas; ?>
      <div class="mc-wrap" id="<?php echo $mcId; ?>">
        <button type="button" class="mc-toggle" onclick="mcOpen('<?php echo $mcId; ?>')">
          <span class="mc-label" id="<?php echo $mcId; ?>-lbl">
            <?php echo count($mcSel) ? count($mcSel) . ' selecionada(s)' : 'Selecione especialidades'; ?>
          </span>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="mc-panel" id="<?php echo $mcId; ?>-panel">
          <input type="text" class="mc-search" placeholder="Buscar..." oninput="mcFilter('<?php echo $mcId; ?>',this.value)">
          <div class="mc-list" id="<?php echo $mcId; ?>-list">
            <?php foreach ($especialidadesDisponiveis as $esp): ?>
            <label class="mc-item">
              <input type="checkbox" name="specialties[]" value="<?php echo View::e($esp); ?>"
                <?php echo in_array($esp, $mcSel) ? 'checked' : ''; ?>
                onchange="mcUpdate('<?php echo $mcId; ?>')">
              <?php echo View::e($esp); ?>
            </label>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <input type="hidden" name="specialties_required" id="specialtiesRequired" value="<?php echo count($mcSel) > 0 ? '1' : ''; ?>" required/>
    </div>

    <div class="form-row">
      <?php
      $estadoSelecionado = !empty($estadosSelecionados) ? $estadosSelecionados[0] : '';
      $cidadeSelecionada = !empty($cidadesSelecionadas) ? $cidadesSelecionadas[0] : '';
      $obrigatorio = true;
      include __DIR__ . '/../_partials/campos-estado-cidade.php';
      ?>
    </div>

    <?php if (!empty($cidadesSelecionadas) && count($cidadesSelecionadas) > 1): ?>
    <div class="form-group">
      <label>Outras cidades de atendimento</label>
      <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:12px">
        <?php foreach (array_slice($cidadesSelecionadas, 1) as $cidade): ?>
          <span style="background:var(--bg-surface);border:1px solid var(--border);padding:6px 12px;border-radius:4px;font-size:.85rem"><?php echo View::e($cidade); ?></span>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <div class="form-group">
      <label>Adicionar mais cidades de atendimento</label>
      <div id="cidadesContainer"></div>
      <button type="button" onclick="adicionarCidadeExtra()" class="btn btn-secondary btn-sm" style="margin-top:6px">+ Adicionar cidade</button>
    </div>

    <div class="form-group">
      <label>CREA / CAU</label>
      <input type="text" name="crea_cau" value="<?php echo View::e($parceiro['crea_cau'] ?? ''); ?>" placeholder="Número do registro (opcional)"/>
    </div>

    <div class="form-group">
      <label>Descrição / Bio *</label>
      <textarea name="bio" required rows="5" placeholder="Descreva sua empresa, experiência e diferenciais..."><?php echo View::e($parceiro['bio'] ?? ''); ?></textarea>
      <small style="color:var(--text-muted);font-size:.75rem;display:block;margin-top:4px">Conte sobre sua empresa, principais trabalhos realizados e o que te diferencia no mercado.</small>
    </div>
  </div>

  <!-- Qualificação -->
  <div class="card" style="margin-bottom:24px;padding:32px">
    <h2 class="card-title" style="margin-bottom:20px"><?php echo View::e(I18n::t('parceiro.qualificacao')); ?></h2>

    <!-- Portfólio -->
    <div class="form-group">
      <label>Portfólio (PDF ou Fotos)</label>

      <?php
      $portfolioExistente = [];
      if (!empty($parceiro['documentos'])) {
          foreach ($parceiro['documentos'] as $doc) {
              if (($doc['type'] ?? '') === 'portfolio') {
                  $portfolioExistente[] = $doc;
              }
          }
      }
      ?>

      <?php if (!empty($portfolioExistente)): ?>
      <div style="margin-bottom:12px">
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:12px;margin-bottom:12px">
          <?php foreach ($portfolioExistente as $doc): ?>
            <?php 
            $isImage = in_array(strtolower(pathinfo($doc['name'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp', 'gif']);
            $fileUrl = '/uploads/parceiros/' . $parceiro['id'] . '/' . $doc['name'];
            ?>
            <div style="position:relative;border:1px solid var(--border);border-radius:4px;overflow:hidden;aspect-ratio:1;background:var(--bg-surface)">
              <?php if ($isImage): ?>
                <img src="<?php echo View::e($fileUrl); ?>" alt="Portfolio" style="width:100%;height:100%;object-fit:cover"/>
              <?php else: ?>
                <div style="display:flex;align-items:center;justify-content:center;height:100%;flex-direction:column;gap:8px;padding:8px;text-align:center">
                  <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                  </svg>
                  <span style="font-size:.7rem;color:var(--text-muted);word-break:break-all"><?php echo View::e(basename($doc['name'])); ?></span>
                </div>
              <?php endif; ?>
              <?php if (!empty($doc['is_verified'])): ?>
                <span class="badge badge-green" style="position:absolute;top:4px;right:4px;font-size:.65rem">✓</span>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <div style="border:2px dashed var(--border);border-radius:8px;padding:24px;text-align:center;cursor:pointer;transition:all .2s" id="portfolioDropzone" onclick="document.getElementById('portfolioInput').click()">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin:0 auto 12px;opacity:.5">
          <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/>
        </svg>
        <p style="margin:0 0 8px;color:var(--text-primary)">Clique ou arraste arquivos aqui</p>
        <small style="color:var(--text-muted);font-size:.75rem">
          Envie 1 arquivo PDF com seu portfólio <strong>ou</strong> no mínimo 6 fotos de trabalhos realizados (JPG, PNG, WebP).
        </small>
      </div>
      <input type="file" name="portfolio[]" id="portfolioInput" multiple accept=".pdf,.jpg,.jpeg,.png,.webp" style="display:none"/>
      
      <div id="portfolioPreview" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:12px;margin-top:12px"></div>
    </div>

    <!-- Certidão de CNPJ ativo -->
    <div class="form-group">
      <label>Certidão de CNPJ ativo</label>

      <?php
      $certidaoCnpj = [];
      if (!empty($parceiro['documentos'])) {
          foreach ($parceiro['documentos'] as $doc) {
              if (($doc['type'] ?? '') === 'certidao_cnpj') {
                  $certidaoCnpj[] = $doc;
              }
          }
      }
      ?>

      <?php if (!empty($certidaoCnpj)): ?>
      <div style="margin-bottom:12px">
        <?php foreach ($certidaoCnpj as $doc): ?>
          <?php 
          $isImage = in_array(strtolower(pathinfo($doc['name'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp', 'gif']);
          $fileUrl = '/uploads/parceiros/' . $parceiro['id'] . '/' . $doc['name'];
          ?>
          <div style="display:flex;gap:12px;align-items:center;padding:12px;border:1px solid var(--border);border-radius:4px;margin-bottom:8px">
            <?php if ($isImage): ?>
              <img src="<?php echo View::e($fileUrl); ?>" alt="Certidão" style="width:80px;height:80px;object-fit:cover;border-radius:4px"/>
            <?php else: ?>
              <div style="width:80px;height:80px;display:flex;align-items:center;justify-content:center;background:var(--bg-surface);border-radius:4px">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                  <polyline points="14 2 14 8 20 8"/>
                </svg>
              </div>
            <?php endif; ?>
            <div style="flex:1">
              <div style="font-size:.85rem;margin-bottom:4px"><?php echo View::e($doc['name']); ?></div>
              <?php if (!empty($doc['is_verified'])): ?>
                <span class="badge badge-green">Verificado</span>
              <?php else: ?>
                <span class="badge badge-gray">Pendente</span>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <div style="background:rgba(184,148,90,.06);border:1px solid rgba(184,148,90,.15);padding:16px;margin-bottom:12px;font-size:.85rem;line-height:1.6">
        <p style="margin-bottom:8px">Para emitir seu certificado, acesse o link:</p>
        <a href="https://solucoes.receita.fazenda.gov.br/servicos/cnpjreva/cnpjreva_solicitacao.asp" target="_blank" rel="noopener" style="color:var(--gold);word-break:break-all">
          https://solucoes.receita.fazenda.gov.br/servicos/cnpjreva/cnpjreva_solicitacao.asp
        </a>
        <p style="margin-top:8px">Insira seu CNPJ e realize o download do cartão. Anexe em seguida.</p>
      </div>

      <div style="border:2px dashed var(--border);border-radius:8px;padding:24px;text-align:center;cursor:pointer;transition:all .2s" id="cnpjDropzone" onclick="document.getElementById('cnpjInput').click()">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin:0 auto 12px;opacity:.5">
          <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/>
        </svg>
        <p style="margin:0 0 8px;color:var(--text-primary)">Clique ou arraste o arquivo aqui</p>
        <small style="color:var(--text-muted);font-size:.75rem">PDF ou imagem da certidão</small>
      </div>
      <input type="file" name="certidao_cnpj" id="cnpjInput" accept=".pdf,.jpg,.jpeg,.png" style="display:none"/>
      
      <div id="cnpjPreview" style="margin-top:12px"></div>
    </div>

    <!-- Outros documentos existentes -->
    <?php
    $outrosDocs = [];
    if (!empty($parceiro['documentos'])) {
        foreach ($parceiro['documentos'] as $doc) {
            if (!in_array($doc['type'] ?? '', ['portfolio', 'certidao_cnpj'])) {
                $outrosDocs[] = $doc;
            }
        }
    }
    ?>
    <?php if (!empty($outrosDocs)): ?>
    <div style="margin-top:16px">
      <label style="font-size:.82rem;font-weight:500">Outros documentos enviados</label>
      <?php foreach ($outrosDocs as $doc): ?>
      <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--border);font-size:.85rem">
        <span><?php echo View::e($doc['name']); ?> <span style="color:var(--text-muted);font-size:.75rem">(<?php echo View::e($doc['type']); ?>)</span></span>
        <?php if (!empty($doc['is_verified'])): ?>
          <span class="badge badge-green">Verificado</span>
        <?php else: ?>
          <span class="badge badge-gray">Pendente</span>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>

  <div style="display:flex;justify-content:flex-end">
    <button type="submit" class="btn btn-primary" id="btnSalvar"><?php echo View::e(I18n::t('geral.salvar')); ?></button>
  </div>
</form>

<style>
.mc-wrap { position: relative; z-index: 1; }
.mc-wrap.mc-open { z-index: 9999; isolation: isolate; }
.mc-toggle {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: var(--bg-input, var(--bg-card));
  border: 1px solid var(--border-color);
  border-radius: 4px;
  padding: 8px 12px;
  cursor: pointer;
  font-size: .85rem;
  color: var(--text-primary);
  text-align: left;
}
.mc-toggle:hover { border-color: var(--gold); }
.mc-panel {
  display: none;
  position: absolute;
  top: calc(100% + 4px);
  left: 0;
  min-width: 100%;
  width: max-content;
  max-width: 360px;
  background: var(--bg-card, #fff);
  background-color: var(--bg-surface, #ffffff);
  border: 1px solid var(--border-color);
  border-radius: 4px;
  z-index: 9999;
  box-shadow: 0 8px 24px rgba(0,0,0,.35);
  transform: translateZ(0);
  isolation: isolate;
}
.mc-panel.open { display: block; }
.mc-search {
  width: 100%;
  border: none;
  border-bottom: 1px solid var(--border-color);
  background: transparent;
  padding: 8px 12px;
  font-size: .82rem;
  color: var(--text-primary);
  outline: none;
  box-sizing: border-box;
}
.mc-list {
  max-height: 220px;
  overflow-y: auto;
  padding: 4px 0;
}
.mc-item {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 7px 14px;
  font-size: .83rem;
  color: var(--text-primary);
  cursor: pointer;
  user-select: none;
}
.mc-item:hover { background: rgba(184,148,90,.08); }
.mc-item input[type=checkbox] { accent-color: var(--gold); width: 14px; height: 14px; flex-shrink: 0; }
.mc-item.hidden { display: none; }
</style>

<script>
// LocalStorage persistence
var formId = 'parceiro-perfil-form';
var form = document.querySelector('form');

// Restaurar dados do localStorage ao carregar
window.addEventListener('DOMContentLoaded', function() {
  var saved = localStorage.getItem(formId);
  if (saved) {
    try {
      var data = JSON.parse(saved);
      Object.keys(data).forEach(function(key) {
        var input = form.querySelector('[name="' + key + '"]');
        if (input && !input.value) {
          if (input.type === 'checkbox') {
            input.checked = data[key];
          } else {
            input.value = data[key];
          }
        }
      });
    } catch (e) {}
  }
});

// Salvar no localStorage a cada mudança
form.addEventListener('input', function(e) {
  var data = {};
  var inputs = form.querySelectorAll('input:not([type=file]):not([type=password]), select, textarea');
  inputs.forEach(function(input) {
    if (input.name) {
      if (input.type === 'checkbox') {
        data[input.name] = input.checked;
      } else {
        data[input.name] = input.value;
      }
    }
  });
  localStorage.setItem(formId, JSON.stringify(data));
});

// Limpar localStorage após submit bem-sucedido
form.addEventListener('submit', function(e) {
  // Validar especialidades
  var especialidadesChecked = document.querySelectorAll('#mc-especialidades input[type=checkbox]:checked');
  var hiddenInput = document.getElementById('specialtiesRequired');
  if (especialidadesChecked.length === 0) {
    e.preventDefault();
    alert('Selecione pelo menos uma especialidade.');
    return false;
  }
  hiddenInput.value = '1';
  
  // Validar portfolio
  var portfolioInput = document.querySelector('input[name="portfolio[]"]');
  if (portfolioInput && portfolioInput.files.length > 0) {
    var files = portfolioInput.files;
    var hasPdf = false;
    var imageCount = 0;
    for (var i = 0; i < files.length; i++) {
      if (files[i].type === 'application/pdf') hasPdf = true;
      else imageCount++;
    }
    if (!hasPdf && imageCount > 0 && imageCount < 6) {
      e.preventDefault();
      alert('Envie no mínimo 6 fotos para o portfólio, ou 1 arquivo PDF.');
      return false;
    }
  }
  
  // Se passou nas validações, limpar localStorage
  setTimeout(function() {
    localStorage.removeItem(formId);
  }, 100);
});

// Multiselect functions
function mcOpen(id) {
  var wrap  = document.getElementById(id);
  var panel = document.getElementById(id + '-panel');
  var isOpen = panel.classList.contains('open');
  document.querySelectorAll('.mc-panel.open').forEach(function(p){ p.classList.remove('open'); });
  document.querySelectorAll('.mc-wrap.mc-open').forEach(function(w){ w.classList.remove('mc-open'); });
  if (!isOpen) {
    panel.classList.add('open');
    wrap.classList.add('mc-open');
    var search = panel.querySelector('.mc-search');
    if (search) { search.value = ''; mcFilter(id, ''); search.focus(); }
  }
}
function mcUpdate(id) {
  var checked = document.querySelectorAll('#' + id + ' input[type=checkbox]:checked');
  var lbl = document.getElementById(id + '-lbl');
  lbl.textContent = checked.length ? checked.length + ' selecionada(s)' : 'Selecione especialidades';
  
  // Atualizar campo hidden para validação
  var hiddenInput = document.getElementById('specialtiesRequired');
  if (hiddenInput) {
    hiddenInput.value = checked.length > 0 ? '1' : '';
  }
}
function mcFilter(id, q) {
  q = q.toLowerCase();
  document.querySelectorAll('#' + id + '-list .mc-item').forEach(function(item) {
    item.classList.toggle('hidden', q !== '' && item.textContent.toLowerCase().indexOf(q) === -1);
  });
}
document.addEventListener('click', function(e) {
  if (!e.target.closest('.mc-wrap')) {
    document.querySelectorAll('.mc-panel.open').forEach(function(p){ p.classList.remove('open'); });
    document.querySelectorAll('.mc-wrap.mc-open').forEach(function(w){ w.classList.remove('mc-open'); });
  }
});

function adicionarCidadeExtra() {
  var container = document.getElementById('cidadesContainer');
  var div = document.createElement('div');
  div.style.cssText = 'display:flex;align-items:center;gap:8px;margin-bottom:6px';
  div.innerHTML = '<input type="text" name="service_cities_extra[]" placeholder="Nome da cidade" style="flex:1"/>'
    + '<button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:1.1rem;padding:4px" aria-label="Remover cidade">&times;</button>';
  container.appendChild(div);
}

// Preview de portfólio
var portfolioInput = document.getElementById('portfolioInput');
var portfolioPreview = document.getElementById('portfolioPreview');
var portfolioDropzone = document.getElementById('portfolioDropzone');

portfolioInput.addEventListener('change', function() {
  previewFiles(this.files, portfolioPreview);
});

portfolioDropzone.addEventListener('dragover', function(e) {
  e.preventDefault();
  this.style.borderColor = 'var(--gold)';
  this.style.background = 'rgba(184,148,90,.05)';
});

portfolioDropzone.addEventListener('dragleave', function(e) {
  e.preventDefault();
  this.style.borderColor = '';
  this.style.background = '';
});

portfolioDropzone.addEventListener('drop', function(e) {
  e.preventDefault();
  this.style.borderColor = '';
  this.style.background = '';
  portfolioInput.files = e.dataTransfer.files;
  previewFiles(e.dataTransfer.files, portfolioPreview);
});

// Preview de CNPJ
var cnpjInput = document.getElementById('cnpjInput');
var cnpjPreview = document.getElementById('cnpjPreview');
var cnpjDropzone = document.getElementById('cnpjDropzone');

cnpjInput.addEventListener('change', function() {
  previewFiles(this.files, cnpjPreview);
});

cnpjDropzone.addEventListener('dragover', function(e) {
  e.preventDefault();
  this.style.borderColor = 'var(--gold)';
  this.style.background = 'rgba(184,148,90,.05)';
});

cnpjDropzone.addEventListener('dragleave', function(e) {
  e.preventDefault();
  this.style.borderColor = '';
  this.style.background = '';
});

cnpjDropzone.addEventListener('drop', function(e) {
  e.preventDefault();
  this.style.borderColor = '';
  this.style.background = '';
  cnpjInput.files = e.dataTransfer.files;
  previewFiles(e.dataTransfer.files, cnpjPreview);
});

function previewFiles(files, container) {
  container.innerHTML = '';
  if (!files || files.length === 0) return;
  
  Array.from(files).forEach(function(file, index) {
    var reader = new FileReader();
    var div = document.createElement('div');
    div.style.cssText = 'position:relative;border:1px solid var(--border);border-radius:4px;overflow:hidden;aspect-ratio:1;background:var(--bg-surface)';
    
    if (file.type.startsWith('image/')) {
      reader.onload = function(e) {
        div.innerHTML = '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover"/>'
          + '<button type="button" onclick="removePreview(this)" style="position:absolute;top:4px;right:4px;background:rgba(0,0,0,.7);color:#fff;border:none;border-radius:50%;width:24px;height:24px;cursor:pointer;font-size:16px;line-height:1;padding:0">&times;</button>';
      };
      reader.readAsDataURL(file);
    } else {
      div.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100%;flex-direction:column;gap:8px;padding:8px;text-align:center">'
        + '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>'
        + '<span style="font-size:.7rem;color:var(--text-muted);word-break:break-all">' + file.name + '</span>'
        + '</div>'
        + '<button type="button" onclick="removePreview(this)" style="position:absolute;top:4px;right:4px;background:rgba(0,0,0,.7);color:#fff;border:none;border-radius:50%;width:24px;height:24px;cursor:pointer;font-size:16px;line-height:1;padding:0">&times;</button>';
    }
    
    div.dataset.index = index;
    container.appendChild(div);
  });
}

function removePreview(btn) {
  var previewDiv = btn.closest('[data-index]');
  var container = previewDiv.parentElement;
  var input = container.id === 'portfolioPreview' ? portfolioInput : cnpjInput;
  var index = parseInt(previewDiv.dataset.index);
  
  var dt = new DataTransfer();
  Array.from(input.files).forEach(function(file, i) {
    if (i !== index) dt.items.add(file);
  });
  input.files = dt.files;
  
  previewDiv.remove();
  
  // Reindexar
  Array.from(container.children).forEach(function(child, i) {
    child.dataset.index = i;
  });
}
</script>
