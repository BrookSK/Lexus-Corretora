<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<section class="inst-hero">
  <span class="lbl reveal"><?php echo View::e(I18n::t('parceiro.titulo')); ?></span>
  <h1 class="disp reveal d1">Faça parte da rede <em>Lexus</em></h1>
  <p class="reveal d2">Cadastre-se e receba oportunidades qualificadas compatíveis com seu perfil.</p>
</section>

<section class="form-section">
  <div class="form-container">
    <?php if (!empty($_SESSION['flash'])): ?>
    <div class="flash-msg flash-<?php echo View::e($_SESSION['flash']['type']); ?>" style="margin-bottom:24px">
      <?php echo View::e($_SESSION['flash']['message']); ?>
    </div>
    <?php unset($_SESSION['flash']); endif; ?>

    <form method="POST" action="/seja-parceiro" enctype="multipart/form-data">
      <?php echo Csrf::campo(); ?>

      <h3 style="font-size:.85rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin-bottom:20px"><?php echo View::e(I18n::t('parceiro.dados_empresa')); ?></h3>
      <div class="form-row">
        <div class="form-group"><label>Nome / Razão Social</label><input type="text" name="name" required/></div>
        <div class="form-group"><label>Nome Fantasia</label><input type="text" name="trade_name"/></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label><?php echo View::e(I18n::t('parceiro.tipo')); ?></label>
          <select name="type"><option value="construtora">Construtora</option><option value="arquiteto">Arquiteto</option><option value="engenheiro">Engenheiro</option><option value="empreiteira">Empreiteira</option><option value="prestador">Prestador</option><option value="fornecedor">Fornecedor</option></select>
        </div>
        <div class="form-group"><label>CPF/CNPJ</label><input type="text" name="document"/></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label><?php echo View::e(I18n::t('auth.email')); ?></label><input type="email" name="email" required/></div>
        <div class="form-group"><label>WhatsApp</label><input type="tel" name="whatsapp"/></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Site</label><input type="url" name="website"/></div>
        <div class="form-group"><label>Instagram</label><input type="text" name="instagram"/></div>
      </div>

      <h3 style="font-size:.85rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin:32px 0 20px"><?php echo View::e(I18n::t('parceiro.dados_prof')); ?></h3>
      <div class="form-group"><label><?php echo View::e(I18n::t('parceiro.especialidades')); ?></label><input type="text" name="specialties" placeholder="Ex: Construção residencial, Reforma comercial"/></div>
      <div class="form-row">
        <div class="form-group"><label><?php echo View::e(I18n::t('parceiro.cidades')); ?></label><input type="text" name="cities" placeholder="Ex: São Paulo, Campinas"/></div>
        <div class="form-group"><label><?php echo View::e(I18n::t('parceiro.tempo_mercado')); ?></label><input type="number" name="years_in_market"/></div>
      </div>
      <div class="form-group"><label>Descrição da Empresa</label><textarea name="description"></textarea></div>

      <h3 style="font-size:.85rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin:32px 0 20px"><?php echo View::e(I18n::t('parceiro.qualificacao')); ?></h3>
      <div class="form-group"><label>Portfólio (PDF)</label><input type="file" name="portfolio" accept=".pdf"/></div>
      <div class="form-group"><label>Documentos</label><input type="file" name="documents[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"/></div>

      <div class="form-submit">
        <button type="submit" class="btn-cta"><?php echo View::e(I18n::t('geral.enviar')); ?></button>
      </div>
    </form>
  </div>
</section>
