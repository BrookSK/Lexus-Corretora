<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('parceiros.editar_parceiro')); ?></h1>
    <p class="section-subtitle"><?php echo View::e($parceiro['name']); ?></p>
  </div>
  <a href="/equipe/parceiros/<?php echo (int)$parceiro['id']; ?>" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<div class="card">
  <form method="POST" action="/equipe/parceiros/<?php echo (int)$parceiro['id']; ?>/atualizar">
    <?php echo Csrf::campo(); ?>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.nome')); ?> *</label>
        <input type="text" name="name" value="<?php echo View::e($parceiro['name']); ?>" required/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('auth.email')); ?> *</label>
        <input type="email" name="email" value="<?php echo View::e($parceiro['email']); ?>" required/>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('parceiros.tipo')); ?> *</label>
        <select name="type" required>
          <?php foreach (['arquiteto','construtora','engenheiro','empreiteira','prestador','fornecedor'] as $t): ?>
          <option value="<?php echo $t; ?>" <?php echo ($parceiro['type'] ?? '') === $t ? 'selected' : ''; ?>><?php echo ucfirst($t); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.status')); ?></label>
        <select name="status">
          <?php foreach (['cadastrado','pendente_analise','em_qualificacao','aprovado','vetriks_ativo','reprovado','suspenso','inativo'] as $s): ?>
          <option value="<?php echo $s; ?>" <?php echo ($parceiro['status'] ?? '') === $s ? 'selected' : ''; ?>><?php echo ucfirst(str_replace('_', ' ', $s)); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.telefone')); ?></label>
        <input type="text" name="phone" value="<?php echo View::e($parceiro['phone'] ?? ''); ?>"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.whatsapp')); ?></label>
        <input type="text" name="whatsapp" value="<?php echo View::e($parceiro['whatsapp'] ?? ''); ?>"/>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.documento')); ?></label>
        <input type="text" name="document" value="<?php echo View::e($parceiro['document'] ?? ''); ?>"/>
      </div>
      <div class="form-group">
        <label>CREA/CAU</label>
        <input type="text" name="crea_cau" value="<?php echo View::e($parceiro['crea_cau'] ?? ''); ?>"/>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.cidade')); ?></label>
        <input type="text" name="city" value="<?php echo View::e($parceiro['city'] ?? ''); ?>"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.estado')); ?></label>
        <input type="text" name="state" value="<?php echo View::e($parceiro['state'] ?? ''); ?>"/>
      </div>
    </div>

    <div class="form-group">
      <label><?php echo View::e(I18n::t('parceiros.especialidades')); ?></label>
      <input type="text" name="specialties" value="<?php echo View::e(is_array($parceiro['specialties'] ?? null) ? implode(', ', $parceiro['specialties']) : ($parceiro['specialties'] ?? '')); ?>"/>
    </div>

    <div class="form-group">
      <label><?php echo View::e(I18n::t('geral.bio')); ?></label>
      <textarea name="bio" rows="3"><?php echo View::e($parceiro['bio'] ?? ''); ?></textarea>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Vetriks</label>
        <select name="is_vetriks">
          <option value="0" <?php echo !($parceiro['is_vetriks'] ?? 0) ? 'selected' : ''; ?>><?php echo View::e(I18n::t('geral.nao')); ?></option>
          <option value="1" <?php echo ($parceiro['is_vetriks'] ?? 0) ? 'selected' : ''; ?>><?php echo View::e(I18n::t('geral.sim')); ?></option>
        </select>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('parceiros.disponibilidade')); ?></label>
        <select name="availability">
          <option value="disponivel" <?php echo ($parceiro['availability'] ?? '') === 'disponivel' ? 'selected' : ''; ?>>Disponível</option>
          <option value="parcial" <?php echo ($parceiro['availability'] ?? '') === 'parcial' ? 'selected' : ''; ?>>Parcial</option>
          <option value="indisponivel" <?php echo ($parceiro['availability'] ?? '') === 'indisponivel' ? 'selected' : ''; ?>>Indisponível</option>
        </select>
      </div>
    </div>

    <div style="margin-top:24px">
      <button type="submit" class="btn btn-primary"><?php echo View::e(I18n::t('geral.salvar')); ?></button>
    </div>
  </form>
</div>
