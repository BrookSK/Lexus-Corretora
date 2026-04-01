<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};

/**
 * Perfil profissional do parceiro
 * Variáveis: $parceiro (array)
 */

$especialidadesDisponiveis = [
    'Construção residencial',
    'Construção comercial',
    'Reforma residencial',
    'Reforma comercial',
    'Reforma industrial',
    'Interiores',
    'Paisagismo',
    'Projeto arquitetônico',
    'Projeto estrutural',
    'Projeto elétrico',
    'Projeto hidráulico',
    'Pintura',
    'Alvenaria',
    'Acabamento',
    'Impermeabilização',
    'Telhados e coberturas',
    'Piscinas',
    'Demolição',
    'Terraplanagem',
    'Instalações elétricas',
    'Instalações hidráulicas',
    'Ar condicionado e climatização',
    'Automação residencial',
    'Energia solar',
    'Serralheria',
    'Marcenaria',
    'Vidraçaria',
    'Gesso e drywall',
    'Pisos e revestimentos',
    'Esquadrias',
];

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
        <label>Razão Social</label>
        <input type="text" name="razao_social" value="<?php echo View::e($parceiro['razao_social'] ?? ''); ?>"/>
      </div>
      <div class="form-group">
        <label>Nome Fantasia *</label>
        <input type="text" name="nome_fantasia" value="<?php echo View::e($parceiro['nome_fantasia'] ?? ''); ?>" required/>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('parceiro.tipo')); ?></label>
        <select name="type">
          <option value="construtora" <?php echo ($parceiro['type'] ?? '') === 'construtora' ? 'selected' : ''; ?>>Construtora</option>
          <option value="arquiteto" <?php echo ($parceiro['type'] ?? '') === 'arquiteto' ? 'selected' : ''; ?>>Arquiteto</option>
          <option value="engenheiro" <?php echo ($parceiro['type'] ?? '') === 'engenheiro' ? 'selected' : ''; ?>>Engenheiro</option>
          <option value="empreiteira" <?php echo ($parceiro['type'] ?? '') === 'empreiteira' ? 'selected' : ''; ?>>Empreiteira</option>
          <option value="prestador" <?php echo ($parceiro['type'] ?? '') === 'prestador' ? 'selected' : ''; ?>>Prestador</option>
          <option value="fornecedor" <?php echo ($parceiro['type'] ?? '') === 'fornecedor' ? 'selected' : ''; ?>>Fornecedor</option>
        </select>
      </div>
      <div class="form-group">
        <label>CNPJ / CPF</label>
        <input type="text" name="document" value="<?php echo View::e($parceiro['document'] ?? ''); ?>"/>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('auth.email')); ?></label>
        <input type="email" name="company_email" value="<?php echo View::e($parceiro['company_email'] ?? $parceiro['email'] ?? ''); ?>"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('contato.telefone')); ?></label>
        <input type="tel" name="phone" value="<?php echo View::e($parceiro['phone'] ?? ''); ?>"/>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>WhatsApp</label>
        <input type="tel" name="whatsapp" value="<?php echo View::e($parceiro['whatsapp'] ?? ''); ?>"/>
      </div>
      <div class="form-group">
        <label>Website</label>
        <input type="text" name="website" value="<?php echo View::e($parceiro['website'] ?? ''); ?>" placeholder="www.suaempresa.com.br"/>
      </div>
    </div>

    <div class="form-group">
      <label>Instagram</label>
      <input type="text" name="instagram" value="<?php echo View::e($parceiro['instagram'] ?? ''); ?>" placeholder="@empresa"/>
    </div>
  </div>

  <!-- Dados Profissionais -->
  <div class="card" style="margin-bottom:24px;padding:32px">
    <h2 class="card-title" style="margin-bottom:20px"><?php echo View::e(I18n::t('parceiro.dados_prof')); ?></h2>

    <div class="form-group">
      <label><?php echo View::e(I18n::t('parceiro.especialidades')); ?></label>
      <select name="specialties[]" multiple style="min-height:160px">
        <?php foreach ($especialidadesDisponiveis as $esp): ?>
        <option value="<?php echo View::e($esp); ?>" <?php echo in_array($esp, $especialidadesSelecionadas) ? 'selected' : ''; ?>><?php echo View::e($esp); ?></option>
        <?php endforeach; ?>
      </select>
      <small style="color:var(--text-muted);font-size:.75rem">Segure Ctrl (ou Cmd) para selecionar múltiplas opções</small>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Estado</label>
        <select name="service_states[]" id="serviceStates" multiple style="min-height:120px">
          <?php foreach ($estadosBrasileiros as $uf => $nomeEstado): ?>
          <option value="<?php echo View::e($uf); ?>" <?php echo in_array($uf, $estadosSelecionados) ? 'selected' : ''; ?>><?php echo View::e($uf . ' — ' . $nomeEstado); ?></option>
          <?php endforeach; ?>
        </select>
        <small style="color:var(--text-muted);font-size:.75rem">Segure Ctrl para selecionar múltiplos estados</small>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('parceiro.cidades')); ?></label>
        <div id="cidadesContainer">
          <?php if (!empty($cidadesSelecionadas)): ?>
            <?php foreach ($cidadesSelecionadas as $cidade): ?>
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px">
              <input type="text" name="service_cities[]" value="<?php echo View::e($cidade); ?>" style="flex:1"/>
              <button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:1.1rem;padding:4px" aria-label="Remover cidade">&times;</button>
            </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px">
              <input type="text" name="service_cities[]" placeholder="Ex: São Paulo" style="flex:1"/>
              <button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:1.1rem;padding:4px" aria-label="Remover cidade">&times;</button>
            </div>
          <?php endif; ?>
        </div>
        <button type="button" onclick="adicionarCidade()" class="btn btn-secondary btn-sm" style="margin-top:6px">+ Adicionar cidade</button>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('parceiro.tempo_mercado')); ?></label>
        <input type="number" name="years_in_market" min="0" value="<?php echo View::e((string)($parceiro['years_in_market'] ?? '')); ?>"/>
      </div>
      <div class="form-group">
        <label>CREA / CAU</label>
        <input type="text" name="crea_cau" value="<?php echo View::e($parceiro['crea_cau'] ?? ''); ?>"/>
      </div>
    </div>

    <div class="form-group">
      <label>Descrição / Bio</label>
      <textarea name="description" placeholder="Descreva sua empresa, experiência e diferenciais..."><?php echo View::e($parceiro['bio'] ?? $parceiro['description'] ?? ''); ?></textarea>
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
        <?php foreach ($portfolioExistente as $doc): ?>
        <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--border);font-size:.85rem">
          <span><?php echo View::e($doc['name']); ?></span>
          <?php if (!empty($doc['is_verified'])): ?>
            <span class="badge badge-green">Verificado</span>
          <?php else: ?>
            <span class="badge badge-gray">Pendente</span>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <input type="file" name="portfolio[]" multiple accept=".pdf,.jpg,.jpeg,.png,.webp"/>
      <small style="color:var(--text-muted);font-size:.75rem">
        Envie 1 arquivo PDF com seu portfólio <strong>ou</strong> no mínimo 6 fotos de trabalhos realizados (JPG, PNG, WebP).
      </small>
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
        <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--border);font-size:.85rem">
          <span><?php echo View::e($doc['name']); ?></span>
          <?php if (!empty($doc['is_verified'])): ?>
            <span class="badge badge-green">Verificado</span>
          <?php else: ?>
            <span class="badge badge-gray">Pendente</span>
          <?php endif; ?>
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

      <input type="file" name="certidao_cnpj" accept=".pdf,.jpg,.jpeg,.png"/>
      <small style="color:var(--text-muted);font-size:.75rem">PDF ou imagem da certidão</small>
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

<script>
function adicionarCidade() {
  var container = document.getElementById('cidadesContainer');
  var div = document.createElement('div');
  div.style.cssText = 'display:flex;align-items:center;gap:8px;margin-bottom:6px';
  div.innerHTML = '<input type="text" name="service_cities[]" placeholder="Ex: São Paulo" style="flex:1"/>'
    + '<button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:1.1rem;padding:4px" aria-label="Remover cidade">&times;</button>';
  container.appendChild(div);
}

document.querySelector('form').addEventListener('submit', function(e) {
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
});
</script>
