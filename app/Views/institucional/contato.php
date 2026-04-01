<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<section class="inst-hero">
  <span class="lbl reveal"><?php echo View::e(I18n::t('pagina.contato')); ?></span>
  <h1 class="disp reveal d1">Conte-nos sobre sua <em>obra</em></h1>
  <p class="reveal d2">Preencha os detalhes abaixo e nossa equipe entrará em contato para estruturar sua demanda.</p>
</section>

<section class="form-section">
  <div class="form-container" style="max-width:780px">
    <?php if (!empty($_SESSION['flash'])): ?>
    <div style="margin-bottom:24px;padding:16px 20px;border-left:3px solid var(--gold);background:rgba(184,148,90,.06);font-size:.9rem">
      <?php echo View::e($_SESSION['flash']['message']); ?>
    </div>
    <?php unset($_SESSION['flash']); endif; ?>

    <form method="POST" action="/contato" enctype="multipart/form-data">
      <?php echo Csrf::campo(); ?>

      <h3 class="form-section-title">Seus Dados</h3>
      <div class="form-row">
        <div class="form-group"><label>Nome completo *</label><input type="text" name="name" required placeholder="Seu nome"/></div>
        <div class="form-group"><label>WhatsApp *</label><input type="tel" name="whatsapp" required placeholder="(00) 00000-0000"/></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>E-mail</label><input type="email" name="email" placeholder="seu@email.com"/></div>
      </div>
      <div class="form-row">
        <?php
        $estadoSelecionado = '';
        $cidadeSelecionada = '';
        $obrigatorio = false;
        include __DIR__ . '/../_partials/campos-estado-cidade.php';
        ?>
      </div>

      <h3 class="form-section-title">Sobre a Obra</h3>
      <div class="form-row">
        <div class="form-group">
          <label>Tipo de obra *</label>
          <select name="work_type" required>
            <option value="">Selecione...</option>
            <option value="reforma">Reforma</option>
            <option value="construcao">Construção</option>
            <option value="ampliacao">Ampliação</option>
            <option value="acabamento">Acabamento</option>
            <option value="retrofit">Retrofit</option>
            <option value="outro">Outro</option>
          </select>
        </div>
        <div class="form-group"><label>Tamanho aproximado (m²)</label><input type="number" name="area_sqm" step="0.01" min="0" placeholder="Ex: 120"/></div>
      </div>
      <div class="form-group"><label>Endereço da obra</label><input type="text" name="address" placeholder="Rua, número, bairro"/></div>

      <h3 class="form-section-title">Documentação</h3>
      <div class="form-row">
        <div class="form-group">
          <label>Possui projeto ou planta?</label>
          <select name="has_project"><option value="nao">Não</option><option value="sim">Sim</option><option value="em_elaboracao">Em elaboração</option></select>
        </div>
        <div class="form-group">
          <label>Possui fotos do estado atual?</label>
          <select name="has_photos"><option value="nao">Não</option><option value="sim">Sim</option></select>
        </div>
      </div>
      <div class="form-group">
        <label>Anexar arquivos (projeto, planta, fotos)</label>
        <input type="file" name="files[]" multiple accept=".pdf,.jpg,.jpeg,.png,.dwg,.doc,.docx,.zip"/>
        <small style="font-size:.75rem;color:rgba(12,12,10,.4);margin-top:4px;display:block">PDF, imagens, DWG, DOC — máx. 10MB por arquivo</small>
      </div>

      <h3 class="form-section-title">Serviços Necessários</h3>
      <div class="form-checks">
        <?php foreach (['Elétrica','Hidráulica','Estrutura','Acabamento','Telhado','Pintura','Piso','Alvenaria','Gesso / Forro','Esquadrias','Paisagismo','Outros'] as $s): ?>
        <label class="form-check-item">
          <input type="checkbox" name="servicos[]" value="<?php echo View::e(strtolower($s)); ?>"/>
          <?php echo View::e($s); ?>
        </label>
        <?php endforeach; ?>
      </div>

      <h3 class="form-section-title">Detalhes Adicionais</h3>
      <div class="form-row">
        <div class="form-group">
          <label>Padrão de acabamento</label>
          <select name="finish_level"><option value="">Selecione...</option><option value="simples">Simples</option><option value="medio">Médio</option><option value="alto">Alto</option></select>
        </div>
        <div class="form-group">
          <label>Fornecimento de material</label>
          <select name="material_supply"><option value="">Selecione...</option><option value="tudo_incluso">Tudo incluso (mão de obra + material)</option><option value="so_mao_obra">Só mão de obra</option><option value="a_definir">A definir</option></select>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Prazo desejado</label>
          <select name="desired_timeline"><option value="">Selecione...</option><option value="urgente">Urgente (até 30 dias)</option><option value="curto">Curto prazo (1-3 meses)</option><option value="medio">Médio prazo (3-6 meses)</option><option value="longo">Longo prazo (6+ meses)</option><option value="flexivel">Flexível</option></select>
        </div>
        <div class="form-group">
          <label>Faixa de investimento</label>
          <select name="budget_range"><option value="">Selecione...</option><option value="ate_50k">Até R$ 50.000</option><option value="50k_100k">R$ 50.000 — R$ 100.000</option><option value="100k_250k">R$ 100.000 — R$ 250.000</option><option value="250k_500k">R$ 250.000 — R$ 500.000</option><option value="500k_1m">R$ 500.000 — R$ 1.000.000</option><option value="acima_1m">Acima de R$ 1.000.000</option><option value="a_definir">A definir</option></select>
        </div>
      </div>

      <div class="form-group">
        <label>Observações adicionais</label>
        <textarea name="message" placeholder="Descreva detalhes importantes, restrições, expectativas..."></textarea>
      </div>

      <div class="form-submit">
        <button type="submit" class="btn-cta" style="width:100%;padding:18px;font-size:.8rem"><?php echo View::e(I18n::t('contato.enviar')); ?></button>
      </div>
    </form>
  </div>
</section>
