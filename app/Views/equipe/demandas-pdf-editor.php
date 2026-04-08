<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};

/**
 * Editor de PDF da Apresentação
 * Permite editar o conteúdo antes de finalizar
 */
?>

<div class="section-header">
  <div>
    <h1 class="section-title">📄 Editar Apresentação PDF</h1>
    <p class="section-subtitle">
      Demanda: <strong><?php echo View::e($demanda['code']); ?></strong> — <?php echo View::e($demanda['title']); ?>
      <?php if ($usandoRascunho && $rascunhoData): ?>
        <br><span style="color:var(--gold);font-size:.85rem">
          ✏️ Rascunho salvo em <?php echo date('d/m/Y H:i', strtotime($rascunhoData)); ?>
        </span>
      <?php endif; ?>
    </p>
  </div>
  <div style="display:flex;gap:12px">
    <button type="button" class="btn btn-secondary" onclick="window.location.href='/equipe/demandas/<?php echo $demanda['id']; ?>'">
      ← Voltar
    </button>
    <button type="button" class="btn btn-warning" onclick="regenerarDescricao()" id="btnRegenerar">
      🔄 Regenerar com Dados Atualizados
    </button>
    <button type="button" class="btn btn-primary" onclick="finalizarPdf()">
      ✓ Finalizar e Visualizar PDF
    </button>
  </div>
</div>

<div class="card" style="margin-bottom:24px">
  <div class="card-header">
    <h2 class="card-title">Memorial Descritivo do Projeto</h2>
    <p style="color:var(--text-muted);font-size:.85rem;margin-top:4px">
      Edite o conteúdo abaixo. As alterações são salvas automaticamente a cada 3 segundos.
    </p>
  </div>
  <div class="card-body">
    <div style="margin-bottom:16px;padding:12px;background:rgba(184,148,90,.08);border:1px solid rgba(184,148,90,.2);border-radius:4px;font-size:.85rem">
      <strong>💡 Dica:</strong> Este texto será usado no PDF final. Você pode editar livremente para ajustar a apresentação.
      Use o botão "Regenerar" para atualizar com os dados mais recentes da demanda (prazo, orçamento, etc).
    </div>
    
    <textarea 
      id="descricaoEditor" 
      rows="20" 
      style="width:100%;padding:16px;font-family:monospace;font-size:14px;line-height:1.6;border:1px solid var(--border);border-radius:4px;background:var(--bg-input);color:var(--text-primary);resize:vertical"
      placeholder="Digite ou edite o memorial descritivo..."
    ><?php echo View::e($descricaoFormal); ?></textarea>
    
    <div style="margin-top:12px;display:flex;justify-content:space-between;align-items:center">
      <div id="statusSalvamento" style="font-size:.85rem;color:var(--text-muted)">
        <span id="statusTexto">Pronto para editar</span>
      </div>
      <div style="font-size:.85rem;color:var(--text-muted)">
        <span id="contadorCaracteres">0</span> caracteres
      </div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h2 class="card-title">📋 Dados da Demanda (Referência)</h2>
  </div>
  <div class="card-body">
    <div class="info-grid">
      <div class="info-item">
        <span class="info-label">Código:</span>
        <span class="info-value"><?php echo View::e($demanda['code']); ?></span>
      </div>
      <div class="info-item">
        <span class="info-label">Título:</span>
        <span class="info-value"><?php echo View::e($demanda['title']); ?></span>
      </div>
      <div class="info-item">
        <span class="info-label">Cliente:</span>
        <span class="info-value"><?php echo View::e($demanda['cliente_nome'] ?? 'Não atribuído'); ?></span>
      </div>
      <div class="info-item">
        <span class="info-label">Localização:</span>
        <span class="info-value"><?php echo View::e(($demanda['city'] ?? '') . ', ' . ($demanda['state'] ?? '')); ?></span>
      </div>
      <div class="info-item">
        <span class="info-label">Área:</span>
        <span class="info-value"><?php echo $demanda['area_sqm'] ? number_format((float)$demanda['area_sqm'], 2, ',', '.') . ' m²' : '—'; ?></span>
      </div>
      <div class="info-item">
        <span class="info-label">Prazo Desejado:</span>
        <span class="info-value"><?php echo !empty($demanda['desired_deadline']) ? date('d/m/Y', strtotime($demanda['desired_deadline'])) : '—'; ?></span>
      </div>
      <div class="info-item">
        <span class="info-label">Orçamento:</span>
        <span class="info-value">
          <?php 
          if (!empty($demanda['budget_min']) || !empty($demanda['budget_max'])) {
            $budget = '';
            if (!empty($demanda['budget_min'])) {
              $budget .= 'R$ ' . number_format((float)$demanda['budget_min'], 2, ',', '.');
            }
            if (!empty($demanda['budget_max'])) {
              if (!empty($demanda['budget_min'])) {
                $budget .= ' a ';
              }
              $budget .= 'R$ ' . number_format((float)$demanda['budget_max'], 2, ',', '.');
            }
            echo View::e($budget);
          } else {
            echo '—';
          }
          ?>
        </span>
      </div>
      <div class="info-item">
        <span class="info-label">Arquivos:</span>
        <span class="info-value"><?php echo count($arquivos); ?> arquivo(s)</span>
      </div>
    </div>
  </div>
</div>

<style>
.info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 16px;
}
.info-item {
  display: flex;
  flex-direction: column;
  gap: 4px;
}
.info-label {
  font-size: .75rem;
  text-transform: uppercase;
  letter-spacing: .5px;
  color: var(--text-muted);
  font-weight: 500;
}
.info-value {
  font-size: .95rem;
  color: var(--text-primary);
}
</style>

<script>
const demandaId = <?php echo $demanda['id']; ?>;
let timeoutSalvamento = null;
let salvando = false;

// Contador de caracteres
const editor = document.getElementById('descricaoEditor');
const contador = document.getElementById('contadorCaracteres');

function atualizarContador() {
  contador.textContent = editor.value.length;
}

editor.addEventListener('input', function() {
  atualizarContador();
  agendarSalvamento();
});

atualizarContador();

// Auto-save
function agendarSalvamento() {
  if (timeoutSalvamento) {
    clearTimeout(timeoutSalvamento);
  }
  
  document.getElementById('statusTexto').textContent = 'Editando...';
  document.getElementById('statusTexto').style.color = 'var(--text-muted)';
  
  timeoutSalvamento = setTimeout(salvarRascunho, 3000);
}

async function salvarRascunho() {
  if (salvando) return;
  
  salvando = true;
  document.getElementById('statusTexto').textContent = 'Salvando...';
  document.getElementById('statusTexto').style.color = 'var(--gold)';
  
  try {
    const response = await fetch(`/equipe/demandas/${demandaId}/pdf/salvar-rascunho`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: new URLSearchParams({
        '_csrf_token': '<?php echo Csrf::gerar(); ?>',
        'descricao_formal': editor.value
      })
    });
    
    const data = await response.json();
    
    if (data.success) {
      document.getElementById('statusTexto').textContent = '✓ Salvo automaticamente';
      document.getElementById('statusTexto').style.color = 'var(--success)';
      
      setTimeout(() => {
        document.getElementById('statusTexto').textContent = 'Pronto para editar';
        document.getElementById('statusTexto').style.color = 'var(--text-muted)';
      }, 2000);
    } else {
      throw new Error(data.message || 'Erro ao salvar');
    }
  } catch (error) {
    console.error('Erro ao salvar rascunho:', error);
    document.getElementById('statusTexto').textContent = '✗ Erro ao salvar';
    document.getElementById('statusTexto').style.color = 'var(--danger)';
  } finally {
    salvando = false;
  }
}

async function regenerarDescricao() {
  if (!confirm('Deseja regenerar a descrição usando os dados mais recentes da demanda? Isso substituirá o conteúdo atual.')) {
    return;
  }
  
  const btnRegenerar = document.getElementById('btnRegenerar');
  btnRegenerar.disabled = true;
  btnRegenerar.textContent = '⏳ Regenerando...';
  
  document.getElementById('statusTexto').textContent = 'Regenerando com IA...';
  document.getElementById('statusTexto').style.color = 'var(--gold)';
  
  try {
    const response = await fetch(`/equipe/demandas/${demandaId}/pdf/regenerar`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: new URLSearchParams({
        '_csrf_token': '<?php echo Csrf::gerar(); ?>'
      })
    });
    
    const data = await response.json();
    
    if (data.success) {
      editor.value = data.descricao_formal;
      atualizarContador();
      document.getElementById('statusTexto').textContent = '✓ Regenerado com sucesso';
      document.getElementById('statusTexto').style.color = 'var(--success)';
      
      alert('Descrição regenerada com sucesso usando os dados mais recentes!');
    } else {
      throw new Error(data.message || 'Erro ao regenerar');
    }
  } catch (error) {
    console.error('Erro ao regenerar:', error);
    alert('Erro ao regenerar descrição: ' + error.message);
    document.getElementById('statusTexto').textContent = '✗ Erro ao regenerar';
    document.getElementById('statusTexto').style.color = 'var(--danger)';
  } finally {
    btnRegenerar.disabled = false;
    btnRegenerar.textContent = '🔄 Regenerar com Dados Atualizados';
  }
}

async function finalizarPdf() {
  // Salvar antes de finalizar
  if (timeoutSalvamento) {
    clearTimeout(timeoutSalvamento);
    await salvarRascunho();
  }
  
  // Redirecionar para visualização final
  window.location.href = `/equipe/demandas/${demandaId}/pdf/finalizar`;
}

// Salvar ao sair da página
window.addEventListener('beforeunload', function(e) {
  if (timeoutSalvamento) {
    e.preventDefault();
    e.returnValue = '';
    salvarRascunho();
  }
});
</script>
