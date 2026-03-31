<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf, Settings};
$secao = $secao ?? 'geral';
$settings = $settings ?? [];
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(ucfirst($secao)); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('sidebar.configuracoes')); ?></p>
  </div>
  <a href="/equipe/configuracoes" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<div class="card">
  <form method="POST" action="/equipe/configuracoes/<?php echo View::e($secao); ?>" enctype="multipart/form-data">
    <?php echo Csrf::campo(); ?>

    <?php if ($secao === 'branding'): ?>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('config.nome_empresa')); ?></label>
        <input type="text" name="sistema.nome" value="<?php echo View::e($settings['sistema.nome'] ?? ''); ?>"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('config.slogan')); ?></label>
        <input type="text" name="sistema.slogan" value="<?php echo View::e($settings['sistema.slogan'] ?? ''); ?>"/>
      </div>
      <div class="form-group">
        <label>Logo</label>
        <?php if (!empty($settings['sistema.logo'])): ?>
          <div style="margin-bottom:10px"><img src="<?php echo View::e($settings['sistema.logo']); ?>" alt="Logo" style="max-height:60px;background:#0C0C0A;padding:8px 16px"></div>
        <?php endif; ?>
        <input type="file" name="logo" accept="image/*"/>
        <small style="font-size:.75rem;color:var(--text-muted)">PNG, SVG, JPG — máx. 5MB</small>
      </div>
      <div class="form-group">
        <label>Favicon</label>
        <?php if (!empty($settings['sistema.favicon'])): ?>
          <div style="margin-bottom:10px"><img src="<?php echo View::e($settings['sistema.favicon']); ?>" alt="Favicon" style="max-height:32px"></div>
        <?php endif; ?>
        <input type="file" name="favicon" accept="image/*,.ico"/>
        <small style="font-size:.75rem;color:var(--text-muted)">ICO, PNG — máx. 5MB</small>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('config.cor_primaria')); ?></label>
        <input type="color" name="sistema.cor_primaria" value="<?php echo View::e($settings['sistema.cor_primaria'] ?? '#B8945A'); ?>" style="width:80px;height:40px;padding:2px;cursor:pointer"/>
      </div>
      <div class="form-group">
        <label>Copyright</label>
        <input type="text" name="sistema.copyright" value="<?php echo View::e($settings['sistema.copyright'] ?? ''); ?>"/>
      </div>

    <?php elseif ($secao === 'smtp'): ?>
      <div class="form-row">
        <div class="form-group">
          <label>SMTP Host</label>
          <input type="text" name="smtp.host" value="<?php echo View::e($settings['smtp.host'] ?? ''); ?>" placeholder="smtp.gmail.com"/>
        </div>
        <div class="form-group">
          <label>Porta</label>
          <input type="number" name="smtp.porta" value="<?php echo View::e((string)($settings['smtp.porta'] ?? '587')); ?>"/>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Usuário</label>
          <input type="text" name="smtp.usuario" value="<?php echo View::e($settings['smtp.usuario'] ?? ''); ?>"/>
        </div>
        <div class="form-group">
          <label>Senha</label>
          <input type="password" name="smtp.senha" value="" placeholder="Deixe vazio para manter"/>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label><?php echo View::e(I18n::t('config.email_remetente')); ?></label>
          <input type="email" name="smtp.de_email" value="<?php echo View::e($settings['smtp.de_email'] ?? ''); ?>"/>
        </div>
        <div class="form-group">
          <label><?php echo View::e(I18n::t('config.nome_remetente')); ?></label>
          <input type="text" name="smtp.de_nome" value="<?php echo View::e($settings['smtp.de_nome'] ?? ''); ?>"/>
        </div>
      </div>

    <?php elseif ($secao === 'seo'): ?>
      <div class="form-group">
        <label>Meta Title</label>
        <input type="text" name="seo.meta_title" value="<?php echo View::e($settings['seo.meta_title'] ?? ''); ?>"/>
      </div>
      <div class="form-group">
        <label>Meta Description</label>
        <textarea name="seo.meta_description" rows="3"><?php echo View::e($settings['seo.meta_description'] ?? ''); ?></textarea>
      </div>
      <div class="form-group">
        <label>Imagem Open Graph</label>
        <?php if (!empty($settings['seo.og_image'])): ?>
          <div style="margin-bottom:10px"><img src="<?php echo View::e($settings['seo.og_image']); ?>" alt="OG Image" style="max-height:80px;border:1px solid var(--border)"></div>
        <?php endif; ?>
        <input type="file" name="og_image" accept="image/*"/>
        <small style="font-size:.75rem;color:var(--text-muted)">Imagem para compartilhamento em redes sociais — 1200x630px recomendado</small>
      </div>
      <div class="form-group">
        <label>Google Analytics ID</label>
        <input type="text" name="seo.ga_id" value="<?php echo View::e($settings['seo.ga_id'] ?? ''); ?>" placeholder="G-XXXXXXXXXX"/>
      </div>

    <?php elseif ($secao === 'cobranca'): ?>
      <h3 style="font-size:.95rem;font-weight:500;margin-bottom:20px;color:var(--gold)">Stripe</h3>
      <div class="form-group">
        <label>Ambiente</label>
        <select name="stripe.mode">
          <option value="sandbox" <?php echo ($settings['stripe.mode'] ?? '') === 'sandbox' ? 'selected' : ''; ?>>Sandbox / Test</option>
          <option value="production" <?php echo ($settings['stripe.mode'] ?? '') === 'production' ? 'selected' : ''; ?>>Produção / Live</option>
        </select>
      </div>
      <div style="background:rgba(184,148,90,.04);border:1px solid rgba(184,148,90,.15);padding:20px;margin-bottom:20px">
        <p style="font-size:.78rem;font-weight:500;text-transform:uppercase;letter-spacing:.1em;color:var(--gold);margin-bottom:12px">Sandbox / Test</p>
        <div class="form-group"><label>Publishable Key (test)</label><input type="text" name="stripe.test_publishable_key" value="<?php echo View::e($settings['stripe.test_publishable_key'] ?? ''); ?>" placeholder="pk_test_..."/></div>
        <div class="form-group"><label>Secret Key (test)</label><input type="password" name="stripe.test_secret_key" value="" placeholder="Deixe vazio para manter"/></div>
        <div class="form-group" style="margin-bottom:0"><label>Webhook Secret (test)</label><input type="password" name="stripe.test_webhook_secret" value="" placeholder="Deixe vazio para manter"/></div>
      </div>
      <div style="background:rgba(34,197,94,.04);border:1px solid rgba(34,197,94,.15);padding:20px;margin-bottom:24px">
        <p style="font-size:.78rem;font-weight:500;text-transform:uppercase;letter-spacing:.1em;color:#166534;margin-bottom:12px">Produção / Live</p>
        <div class="form-group"><label>Publishable Key (live)</label><input type="text" name="stripe.live_publishable_key" value="<?php echo View::e($settings['stripe.live_publishable_key'] ?? ''); ?>" placeholder="pk_live_..."/></div>
        <div class="form-group"><label>Secret Key (live)</label><input type="password" name="stripe.live_secret_key" value="" placeholder="Deixe vazio para manter"/></div>
        <div class="form-group" style="margin-bottom:0"><label>Webhook Secret (live)</label><input type="password" name="stripe.live_webhook_secret" value="" placeholder="Deixe vazio para manter"/></div>
      </div>

      <h3 style="font-size:.95rem;font-weight:500;margin-bottom:20px;color:var(--gold)">Asaas</h3>
      <div class="form-group">
        <label>Ambiente</label>
        <select name="asaas.mode">
          <option value="sandbox" <?php echo ($settings['asaas.mode'] ?? '') === 'sandbox' ? 'selected' : ''; ?>>Sandbox</option>
          <option value="production" <?php echo ($settings['asaas.mode'] ?? '') === 'production' ? 'selected' : ''; ?>>Produção</option>
        </select>
      </div>
      <div style="background:rgba(184,148,90,.04);border:1px solid rgba(184,148,90,.15);padding:20px;margin-bottom:20px">
        <p style="font-size:.78rem;font-weight:500;text-transform:uppercase;letter-spacing:.1em;color:var(--gold);margin-bottom:12px">Sandbox</p>
        <div class="form-group"><label>API Key (sandbox)</label><input type="password" name="asaas.sandbox_api_key" value="" placeholder="Deixe vazio para manter"/></div>
        <div class="form-group" style="margin-bottom:0"><label>Webhook Token (sandbox)</label><input type="password" name="asaas.sandbox_webhook_token" value="" placeholder="Deixe vazio para manter"/></div>
      </div>
      <div style="background:rgba(34,197,94,.04);border:1px solid rgba(34,197,94,.15);padding:20px;margin-bottom:24px">
        <p style="font-size:.78rem;font-weight:500;text-transform:uppercase;letter-spacing:.1em;color:#166534;margin-bottom:12px">Produção</p>
        <div class="form-group"><label>API Key (produção)</label><input type="password" name="asaas.production_api_key" value="" placeholder="Deixe vazio para manter"/></div>
        <div class="form-group" style="margin-bottom:0"><label>Webhook Token (produção)</label><input type="password" name="asaas.production_webhook_token" value="" placeholder="Deixe vazio para manter"/></div>
      </div>

      <div class="card" style="background:var(--bg);border-left:3px solid var(--gold);padding:20px;margin-bottom:0">
        <p style="font-size:.82rem;font-weight:500;margin-bottom:8px">URLs de Webhook</p>
        <p style="font-size:.82rem;color:var(--text-muted)">Stripe: <code style="background:rgba(0,0,0,.06);padding:2px 6px"><?php echo View::e(($_SERVER['REQUEST_SCHEME'] ?? 'https') . '://' . ($_SERVER['HTTP_HOST'] ?? '')); ?>/webhooks/stripe</code></p>
        <p style="font-size:.82rem;color:var(--text-muted);margin-top:4px">Asaas: <code style="background:rgba(0,0,0,.06);padding:2px 6px"><?php echo View::e(($_SERVER['REQUEST_SCHEME'] ?? 'https') . '://' . ($_SERVER['HTTP_HOST'] ?? '')); ?>/webhooks/asaas</code></p>
      </div>

    <?php elseif ($secao === 'legal'): ?>
      <div class="form-group">
        <label>Termos de Uso (HTML)</label>
        <textarea name="legal.termos" rows="20" style="font-family:monospace;font-size:.82rem"><?php echo View::e($settings['legal.termos'] ?? ''); ?></textarea>
        <small style="font-size:.75rem;color:var(--text-muted)">Aceita HTML. Editável diretamente.</small>
      </div>
      <div class="form-group">
        <label>Política de Privacidade (HTML)</label>
        <textarea name="legal.privacidade" rows="20" style="font-family:monospace;font-size:.82rem"><?php echo View::e($settings['legal.privacidade'] ?? ''); ?></textarea>
        <small style="font-size:.75rem;color:var(--text-muted)">Aceita HTML. Editável diretamente.</small>
      </div>

    <?php elseif ($secao === 'trello'): ?>
      <?php
      $trelloToken = $settings['trello.api_token'] ?? '';
      $trelloKey = $settings['trello.api_key'] ?? '';
      $trelloConectado = !empty($trelloToken) && !empty($trelloKey);
      $listId = $settings['trello.list_id'] ?? '';
      $baseUrl = ($_SERVER['REQUEST_SCHEME'] ?? 'https') . '://' . ($_SERVER['HTTP_HOST'] ?? '');
      ?>

      <?php if (!$trelloConectado): ?>
        <!-- Passo 1: API Key -->
        <div style="background:rgba(184,148,90,.04);border:1px solid rgba(184,148,90,.15);padding:24px;margin-bottom:24px">
          <p style="font-size:.92rem;font-weight:500;margin-bottom:12px">Passo 1: API Key</p>
          <p style="font-size:.85rem;color:var(--text-muted);margin-bottom:16px">
            Acesse o link abaixo, faça login no Trello, e copie a API Key que aparece na página.
          </p>
          <a href="https://trello.com/power-ups/admin" target="_blank" class="btn btn-secondary" style="margin-bottom:16px">Abrir Trello Power-Ups →</a>
          <div class="form-group" style="margin-top:16px;margin-bottom:0">
            <label>Cole sua API Key aqui</label>
            <input type="text" name="trello.api_key" value="<?php echo View::e($trelloKey); ?>" placeholder="Ex: a1b2c3d4e5f6..."/>
          </div>
        </div>

        <!-- Passo 2: Conectar (só aparece se já tem API Key) -->
        <?php if (!empty($trelloKey)): ?>
        <div style="background:rgba(34,197,94,.04);border:1px solid rgba(34,197,94,.15);padding:24px;margin-bottom:24px">
          <p style="font-size:.92rem;font-weight:500;margin-bottom:12px">Passo 2: Autorizar</p>
          <p style="font-size:.85rem;color:var(--text-muted);margin-bottom:16px">
            Clique no botão abaixo para autorizar a Lexus a criar cards no seu Trello.
          </p>
          <a href="https://trello.com/1/authorize?expiration=never&scope=read,write&response_type=token&key=<?php echo View::e($trelloKey); ?>&return_url=<?php echo View::e($baseUrl); ?>/equipe/trello/callback&name=Lexus+Corretora" class="btn btn-primary">
            Conectar Trello
          </a>
        </div>
        <?php endif; ?>

        <p style="font-size:.82rem;color:var(--text-muted)">Salve a API Key primeiro, depois clique em "Conectar Trello".</p>

      <?php else: ?>
        <!-- Conectado -->
        <div style="display:flex;align-items:center;gap:12px;padding:20px;background:rgba(34,197,94,.06);border:1px solid rgba(34,197,94,.2);margin-bottom:24px">
          <span style="width:12px;height:12px;border-radius:50%;background:#22c55e;flex-shrink:0"></span>
          <span style="font-size:.92rem;font-weight:500;color:#166534">Trello conectado</span>
          <form method="POST" action="/equipe/trello/desconectar" style="margin-left:auto">
            <?php echo Csrf::campo(); ?>
            <button type="submit" class="btn btn-danger btn-sm">Desconectar</button>
          </form>
        </div>

        <!-- Seleção de Board e Lista -->
        <div id="trelloBoardsArea">
          <p style="font-size:.92rem;font-weight:500;margin-bottom:8px">Escolha onde os cards serão criados</p>
          <p style="font-size:.82rem;color:var(--text-muted);margin-bottom:16px">Selecione o board e a lista. Você pode usar a mesma lista para tudo ou separar por tipo.</p>

          <div id="trelloLoading" style="padding:20px;text-align:center;color:var(--text-muted)">Carregando boards...</div>
          <div id="trelloSelects" style="display:none">
            <div class="form-group">
              <label>Board</label>
              <select id="trelloBoardSelect" onchange="trelloLoadLists(this.value)">
                <option value="">Selecione um board...</option>
              </select>
            </div>
            <div class="form-group">
              <label>Lista padrão (todos os cards)</label>
              <select id="trelloListDefault" name="list_id">
                <option value="">Selecione uma lista...</option>
              </select>
            </div>
            <p style="font-size:.78rem;font-weight:500;text-transform:uppercase;letter-spacing:.1em;color:var(--gold);margin:20px 0 12px">Listas por tipo (opcional)</p>
            <div class="form-row">
              <div class="form-group">
                <label>Lista — Contatos</label>
                <select id="trelloListContato" name="list_contato"><option value="">Usar padrão</option></select>
              </div>
              <div class="form-group">
                <label>Lista — Demandas</label>
                <select id="trelloListDemanda" name="list_demanda"><option value="">Usar padrão</option></select>
              </div>
            </div>
            <div class="form-group">
              <label>Lista — Parceiros</label>
              <select id="trelloListParceiro" name="list_parceiro"><option value="">Usar padrão</option></select>
            </div>
          </div>
        </div>

        <?php if (!empty($listId)): ?>
        <div style="margin-top:16px;padding:16px;background:var(--bg);border-left:3px solid var(--gold)">
          <p style="font-size:.82rem;color:var(--text-muted)">Lista padrão configurada: <code style="background:rgba(0,0,0,.06);padding:2px 6px"><?php echo View::e($listId); ?></code></p>
        </div>
        <?php endif; ?>

        <script>
        // Mudar action do form principal para salvar listas
        (function(){
          var forms=document.querySelectorAll('form');
          for(var i=0;i<forms.length;i++){
            if(forms[i].action.indexOf('configuracoes/trello')!==-1){
              forms[i].action='/equipe/trello/salvar-lista';
              break;
            }
          }
        })();
        (function(){
          fetch('/equipe/trello/boards').then(r=>r.json()).then(function(boards){
            document.getElementById('trelloLoading').style.display='none';
            document.getElementById('trelloSelects').style.display='block';
            var sel=document.getElementById('trelloBoardSelect');
            boards.forEach(function(b){
              var o=document.createElement('option');o.value=b.id;o.textContent=b.name;
              o.dataset.lists=JSON.stringify(b.lists);sel.appendChild(o);
            });
            // Se já tem lista configurada, tentar selecionar o board certo
            var currentList='<?php echo View::e($listId); ?>';
            if(currentList){
              boards.forEach(function(b){
                b.lists.forEach(function(l){
                  if(l.id===currentList){sel.value=b.id;trelloLoadLists(b.id)}
                });
              });
            }
          }).catch(function(){
            document.getElementById('trelloLoading').textContent='Erro ao carregar boards.';
          });
        })();
        function trelloLoadLists(boardId){
          var sel=document.getElementById('trelloBoardSelect');
          var opt=sel.querySelector('option[value="'+boardId+'"]');
          if(!opt)return;
          var lists=JSON.parse(opt.dataset.lists||'[]');
          var selects=['trelloListDefault','trelloListContato','trelloListDemanda','trelloListParceiro'];
          var currentList='<?php echo View::e($listId); ?>';
          var currentContato='<?php echo View::e($settings['trello.list_contato'] ?? ''); ?>';
          var currentDemanda='<?php echo View::e($settings['trello.list_demanda'] ?? ''); ?>';
          var currentParceiro='<?php echo View::e($settings['trello.list_parceiro'] ?? ''); ?>';
          var currents=[currentList,currentContato,currentDemanda,currentParceiro];
          selects.forEach(function(id,i){
            var s=document.getElementById(id);
            s.innerHTML=i===0?'<option value="">Selecione...</option>':'<option value="">Usar padrão</option>';
            lists.forEach(function(l){
              var o=document.createElement('option');o.value=l.id;o.textContent=l.name;
              if(l.id===currents[i])o.selected=true;
              s.appendChild(o);
            });
          });
        }
        </script>
      <?php endif; ?>

    <?php elseif ($secao === 'geral'): ?>
      <div class="form-row">
        <div class="form-group">
          <label>Idioma Padrão</label>
          <select name="sistema.idioma_padrao">
            <option value="pt-BR" <?php echo ($settings['sistema.idioma_padrao'] ?? '') === 'pt-BR' ? 'selected' : ''; ?>>Português (BR)</option>
            <option value="en-US" <?php echo ($settings['sistema.idioma_padrao'] ?? '') === 'en-US' ? 'selected' : ''; ?>>English (US)</option>
            <option value="es-ES" <?php echo ($settings['sistema.idioma_padrao'] ?? '') === 'es-ES' ? 'selected' : ''; ?>>Español (ES)</option>
          </select>
        </div>
        <div class="form-group">
          <label>Moeda Padrão</label>
          <select name="sistema.moeda_padrao">
            <option value="BRL" <?php echo ($settings['sistema.moeda_padrao'] ?? '') === 'BRL' ? 'selected' : ''; ?>>BRL — Real</option>
            <option value="USD" <?php echo ($settings['sistema.moeda_padrao'] ?? '') === 'USD' ? 'selected' : ''; ?>>USD — Dólar</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label>Timezone</label>
        <input type="text" name="sistema.timezone" value="<?php echo View::e($settings['sistema.timezone'] ?? 'America/Sao_Paulo'); ?>" placeholder="America/Sao_Paulo"/>
      </div>

    <?php else: ?>
      <p style="color:var(--text-muted);font-size:.88rem">Seção "<?php echo View::e($secao); ?>" em desenvolvimento.</p>
    <?php endif; ?>

    <div style="margin-top:24px">
      <button type="submit" class="btn btn-primary"><?php echo View::e(I18n::t('geral.salvar')); ?></button>
    </div>
  </form>
</div>
