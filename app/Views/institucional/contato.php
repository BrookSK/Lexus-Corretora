<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<section class="inst-hero">
  <span class="lbl reveal"><?php echo View::e(I18n::t('pagina.contato')); ?></span>
  <h1 class="disp reveal d1">Entre em <em>contato</em> conosco</h1>
  <p class="reveal d2">Tire suas dúvidas, solicite informações ou envie sugestões. Nossa equipe responderá em breve.</p>
</section>

<section class="form-section">
  <div class="form-container" style="max-width:680px">
    <?php if (!empty($_SESSION['flash'])): ?>
    <div style="margin-bottom:24px;padding:16px 20px;border-left:3px solid var(--gold);background:rgba(184,148,90,.06);font-size:.9rem">
      <?php echo View::e($_SESSION['flash']['message']); ?>
    </div>
    <?php unset($_SESSION['flash']); endif; ?>

    <form method="POST" action="/contato">
      <?php echo Csrf::campo(); ?>

      <div class="form-group">
        <label>Nome completo <span style="color:var(--gold)">*</span></label>
        <input type="text" name="name" required placeholder="Seu nome completo"/>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>E-mail <span style="color:var(--gold)">*</span></label>
          <input type="email" name="email" required placeholder="seu@email.com"/>
        </div>
        <div class="form-group">
          <label>Telefone / WhatsApp</label>
          <input type="tel" name="phone" placeholder="(00) 00000-0000"/>
        </div>
      </div>

      <div class="form-group">
        <label>Assunto <span style="color:var(--gold)">*</span></label>
        <select name="subject" required>
          <option value="">Selecione o assunto...</option>
          <option value="duvida">Dúvida sobre serviços</option>
          <option value="orcamento">Solicitar orçamento</option>
          <option value="parceria">Proposta de parceria</option>
          <option value="suporte">Suporte técnico</option>
          <option value="sugestao">Sugestão ou feedback</option>
          <option value="outro">Outro assunto</option>
        </select>
      </div>

      <div class="form-group">
        <label>Mensagem <span style="color:var(--gold)">*</span></label>
        <textarea name="message" required rows="8" placeholder="Escreva sua mensagem aqui..."></textarea>
      </div>

      <div class="form-submit">
        <button type="submit" class="btn-cta" style="width:100%;padding:18px;font-size:.8rem">
          <?php echo View::e(I18n::t('contato.enviar')); ?>
        </button>
      </div>
    </form>

    <!-- Informações de contato -->
    <div style="margin-top:64px;padding-top:48px;border-top:1px solid rgba(0,0,0,.08)">
      <h3 style="font-family:'Cormorant Garamond',serif;font-size:1.6rem;font-weight:300;margin-bottom:24px;color:var(--black)">Outras formas de contato</h3>
      
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:24px">
        <div>
          <div style="font-size:.7rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin-bottom:8px;font-weight:500">E-mail</div>
          <a href="mailto:contato@lexuscorretora.com" style="font-size:.88rem;color:var(--black);text-decoration:none">contato@lexuscorretora.com</a>
        </div>
        
        <div>
          <div style="font-size:.7rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin-bottom:8px;font-weight:500">WhatsApp</div>
          <a href="https://wa.me/5511999999999" target="_blank" style="font-size:.88rem;color:var(--black);text-decoration:none">(11) 99999-9999</a>
        </div>
        
        <div>
          <div style="font-size:.7rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin-bottom:8px;font-weight:500">Horário de atendimento</div>
          <p style="font-size:.88rem;color:rgba(12,12,10,.6);margin:0">Seg - Sex: 9h às 18h</p>
        </div>
      </div>
    </div>
  </div>
</section>
