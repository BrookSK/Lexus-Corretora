<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
require __DIR__ . '/../_partials/categorias.php';
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('parceiros.novo_parceiro')); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('parceiros.subtitulo_criar')); ?></p>
  </div>
  <a href="/equipe/parceiros" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<div class="card">
  <form method="POST" action="/equipe/parceiros/novo">
    <?php echo Csrf::campo(); ?>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.nome')); ?> *</label>
        <input type="text" name="name" required/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('auth.email')); ?> *</label>
        <input type="email" name="email" required/>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('auth.senha')); ?> *</label>
        <input type="password" name="password" required minlength="8"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('parceiros.tipo')); ?> *</label>
        <select name="type" required>
          <option value="arquiteto">Arquiteto</option>
          <option value="construtora">Construtora</option>
          <option value="engenheiro">Engenheiro</option>
          <option value="empreiteira">Empreiteira</option>
          <option value="prestador" selected>Prestador</option>
          <option value="fornecedor">Fornecedor</option>
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.telefone')); ?></label>
        <input type="text" name="phone"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.whatsapp')); ?></label>
        <input type="text" name="whatsapp"/>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.documento')); ?></label>
        <input type="text" name="document"/>
      </div>
      <div class="form-group">
        <label>CREA/CAU</label>
        <input type="text" name="crea_cau"/>
      </div>
    </div>

    <div class="form-row">
      <?php
      $estadoSelecionado = '';
      $cidadeSelecionada = '';
      $obrigatorio = false;
      include __DIR__ . '/../_partials/campos-estado-cidade.php';
      ?>
    </div>

    <div class="form-group">
      <label><?php echo View::e(I18n::t('parceiros.especialidades')); ?></label>
      <div class="mc-wrap" id="mc-esp">
        <button type="button" class="mc-toggle" onclick="mcOpen('mc-esp')">
          <span class="mc-label" id="mc-esp-lbl">Selecione especialidades</span>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="mc-panel" id="mc-esp-panel">
          <input type="text" class="mc-search" placeholder="Buscar..." oninput="mcFilter('mc-esp',this.value)">
          <div class="mc-list" id="mc-esp-list">
            <?php foreach ($CATEGORIAS_NICHO as $cat): ?>
            <label class="mc-item">
              <input type="checkbox" name="specialties[]" value="<?php echo View::e($cat); ?>" onchange="mcUpdate('mc-esp')">
              <?php echo View::e($cat); ?>
            </label>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label><?php echo View::e(I18n::t('geral.bio')); ?></label>
      <textarea name="bio" rows="3"></textarea>
    </div>

    <div style="margin-top:24px">
      <button type="submit" class="btn btn-primary"><?php echo View::e(I18n::t('geral.salvar')); ?></button>
    </div>
  </form>
</div>

<?php require __DIR__ . '/../_partials/mc-dropdown.js.php'; ?>
