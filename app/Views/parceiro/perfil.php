<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};

/**
 * Perfil profissional do parceiro
 * Variáveis: $parceiro (array)
 */
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar_par.perfil')); ?></h1>
    <p class="section-subtitle">Informações da empresa e perfil profissional</p>
  </div>
</div>

<form method="POST" action="/parceiro/perfil/salvar" enctype="multipart/form-data">
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
        <input type="url" name="website" value="<?php echo View::e($parceiro['website'] ?? ''); ?>" placeholder="https://"/>
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
      <input type="text" name="specialties" value="<?php echo View::e(is_array($parceiro['specialties'] ?? null) ? implode(', ', $parceiro['specialties']) : ($parceiro['specialties'] ?? '')); ?>" placeholder="Ex: Construção residencial, Reforma comercial, Interiores"/>
      <small style="color:var(--text-muted);font-size:.75rem">Separe por vírgula</small>
    </div>

    <div class="form-group">
      <label><?php echo View::e(I18n::t('parceiro.areas_atuacao')); ?></label>
      <input type="text" name="service_areas" value="<?php echo View::e(is_array($parceiro['service_areas'] ?? null) ? implode(', ', $parceiro['service_areas']) : ($parceiro['service_areas'] ?? '')); ?>" placeholder="Ex: Residencial, Comercial, Industrial"/>
      <small style="color:var(--text-muted);font-size:.75rem">Separe por vírgula</small>
    </div>

    <div class="form-group">
      <label><?php echo View::e(I18n::t('parceiro.cidades')); ?></label>
      <input type="text" name="service_cities" value="<?php echo View::e(is_array($parceiro['service_cities'] ?? null) ? implode(', ', $parceiro['service_cities']) : ($parceiro['service_cities'] ?? '')); ?>" placeholder="Ex: São Paulo, Campinas, Santos"/>
      <small style="color:var(--text-muted);font-size:.75rem">Separe por vírgula</small>
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

  <!-- Documentos de Qualificação -->
  <div class="card" style="margin-bottom:24px;padding:32px">
    <h2 class="card-title" style="margin-bottom:20px"><?php echo View::e(I18n::t('parceiro.qualificacao')); ?></h2>

    <?php if (!empty($parceiro['documentos'])): ?>
    <div style="margin-bottom:16px">
      <?php foreach ($parceiro['documentos'] as $doc): ?>
      <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid var(--border);font-size:.88rem">
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

    <div class="form-group">
      <label>Enviar Documentos</label>
      <input type="file" name="qualification_docs[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"/>
      <small style="color:var(--text-muted);font-size:.75rem">Alvará, certificações, portfólio, referências — PDF, imagens, DOC</small>
    </div>
  </div>

  <div style="display:flex;justify-content:flex-end">
    <button type="submit" class="btn btn-primary"><?php echo View::e(I18n::t('geral.salvar')); ?></button>
  </div>
</form>
