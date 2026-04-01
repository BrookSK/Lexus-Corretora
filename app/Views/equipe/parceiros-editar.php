<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
require __DIR__ . '/../_partials/categorias.php';

$espSelecionadas = [];
if (!empty($parceiro['specialties'])) {
    $raw = $parceiro['specialties'];
    if (is_string($raw)) {
        $decoded = json_decode($raw, true);
        $espSelecionadas = is_array($decoded) ? $decoded : array_map('trim', explode(',', $raw));
    } elseif (is_array($raw)) {
        $espSelecionadas = $raw;
    }
}
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('parceiros.editar_parceiro')); ?></h1>
    <p class="section-subtitle"><?php echo View::e($parceiro['name']); ?></p>
  </div>
  <a href="/equipe/parceiros/<?php echo (int)$parceiro['id']; ?>" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<div class="card">
  <form method="POST" action="/equipe/parceiros/<?php echo (int)$parceiro['id']; ?>/editar">
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
      <?php
      $estadoSelecionado = $parceiro['state'] ?? '';
      $cidadeSelecionada = $parceiro['city'] ?? '';
      $obrigatorio = false;
      include __DIR__ . '/../_partials/campos-estado-cidade.php';
      ?>
    </div>

    <div class="form-group">
      <label><?php echo View::e(I18n::t('parceiros.especialidades')); ?></label>
      <div class="mc-wrap" id="mc-esp">
        <button type="button" class="mc-toggle" onclick="mcOpen('mc-esp')">
          <span class="mc-label" id="mc-esp-lbl">
            <?php echo count($espSelecionadas) ? count($espSelecionadas) . ' selecionada(s)' : 'Selecione especialidades'; ?>
          </span>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="mc-panel" id="mc-esp-panel">
          <input type="text" class="mc-search" placeholder="Buscar..." oninput="mcFilter('mc-esp',this.value)">
          <div class="mc-list" id="mc-esp-list">
            <?php foreach ($CATEGORIAS_NICHO as $cat): ?>
            <label class="mc-item">
              <input type="checkbox" name="specialties[]" value="<?php echo View::e($cat); ?>"
                <?php echo in_array($cat, $espSelecionadas) ? 'checked' : ''; ?>
                onchange="mcUpdate('mc-esp')">
              <?php echo View::e($cat); ?>
            </label>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
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

    <div class="form-row" style="margin-top:8px">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('auth.nova_senha')); ?></label>
        <input type="password" name="password" id="parceiro_password" autocomplete="new-password" placeholder="Deixe em branco para não alterar"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('auth.confirmar_senha')); ?></label>
        <input type="password" id="parceiro_password_confirm" autocomplete="new-password" placeholder="Repita a nova senha"/>
      </div>
    </div>

    <div style="margin-top:24px">
      <button type="submit" class="btn btn-primary"><?php echo View::e(I18n::t('geral.salvar')); ?></button>
    </div>
  </form>
<script>
(function(){
  document.querySelector('form[action*="/editar"]').addEventListener('submit', function(e){
    var p = document.getElementById('parceiro_password');
    var c = document.getElementById('parceiro_password_confirm');
    if (p.value !== '' && p.value !== c.value) {
      e.preventDefault();
      alert('As senhas não coincidem.');
      c.focus();
    }
  });
})();
</script>
<?php require __DIR__ . '/../_partials/mc-dropdown.js.php'; ?>
</div>
