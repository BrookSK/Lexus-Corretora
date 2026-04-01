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
        <div class="form-group"><label>Site</label><input type="text" name="website" placeholder="www.suaempresa.com.br"/></div>
        <div class="form-group"><label>Instagram</label><input type="text" name="instagram"/></div>
      </div>

      <h3 style="font-size:.85rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin:32px 0 20px"><?php echo View::e(I18n::t('parceiro.dados_prof')); ?></h3>
      <div class="form-group"><label><?php echo View::e(I18n::t('parceiro.especialidades')); ?></label><input type="text" name="specialties" placeholder="Ex: Construção residencial, Reforma comercial"/></div>
      <div class="form-row">
        <?php
        $estadoSelecionado = '';
        $cidadeSelecionada = '';
        $obrigatorio = false;
        include __DIR__ . '/../_partials/campos-estado-cidade.php';
        ?>
      </div>
      <div class="form-group"><label><?php echo View::e(I18n::t('parceiro.tempo_mercado')); ?></label><input type="number" name="years_in_market"/></div>
      <div class="form-group"><label>Descrição da Empresa</label><textarea name="description"></textarea></div>

      <h3 style="font-size:.85rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin:32px 0 20px"><?php echo View::e(I18n::t('parceiro.qualificacao')); ?></h3>
      <div class="form-group"><label>Portfólio (PDF ou Fotos)</label><input type="file" name="portfolio[]" multiple accept=".pdf,.jpg,.jpeg,.png,.webp"/><small style="font-size:.75rem;color:rgba(12,12,10,.4);display:block;margin-top:4px">Envie 1 PDF ou no mínimo 6 fotos de trabalhos realizados</small></div>
      <div class="form-group">
        <label>Certidão de CNPJ ativo</label>
        <div style="background:rgba(184,148,90,.06);border:1px solid rgba(184,148,90,.15);padding:12px;margin-bottom:8px;font-size:.82rem;line-height:1.5">
          Para emitir, acesse: <a href="https://solucoes.receita.fazenda.gov.br/servicos/cnpjreva/cnpjreva_solicitacao.asp" target="_blank" rel="noopener" style="color:var(--gold)">Receita Federal</a>. Insira seu CNPJ, faça o download do cartão e anexe abaixo.
        </div>
        <input type="file" name="certidao_cnpj" accept=".pdf,.jpg,.jpeg,.png"/>
      </div>

      <div class="form-submit">
        <button type="submit" class="btn-cta"><?php echo View::e(I18n::t('geral.enviar')); ?></button>
      </div>
    </form>
  </div>
</section>
