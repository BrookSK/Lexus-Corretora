<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div id="cookieBanner" class="cookie-banner" style="display:none">
  <div class="cookie-content">
    <p><?php echo View::e(I18n::t('cookies.mensagem')); ?></p>
    <div class="cookie-actions">
      <button onclick="acceptCookies('all')" class="btn-cta"><?php echo View::e(I18n::t('cookies.aceitar')); ?></button>
      <button onclick="acceptCookies('necessary')" class="btn-out"><?php echo View::e(I18n::t('cookies.rejeitar')); ?></button>
      <button onclick="openCookieSettings()" class="btn-link"><?php echo View::e(I18n::t('cookies.configurar')); ?></button>
    </div>
  </div>
</div>

<div id="cookieModal" class="cookie-modal" style="display:none">
  <div class="cookie-modal-content">
    <h3><?php echo View::e(I18n::t('cookies.titulo')); ?></h3>
    <div class="cookie-category">
      <label><input type="checkbox" checked disabled> <?php echo View::e(I18n::t('cookies.necessarios')); ?></label>
    </div>
    <div class="cookie-category">
      <label><input type="checkbox" id="ckAnalytics"> <?php echo View::e(I18n::t('cookies.analytics')); ?></label>
    </div>
    <div class="cookie-category">
      <label><input type="checkbox" id="ckMarketing"> <?php echo View::e(I18n::t('cookies.marketing')); ?></label>
    </div>
    <div class="cookie-category">
      <label><input type="checkbox" id="ckPreferences"> <?php echo View::e(I18n::t('cookies.preferencias')); ?></label>
    </div>
    <button onclick="saveCookieSettings()" class="btn-cta"><?php echo View::e(I18n::t('cookies.salvar')); ?></button>
  </div>
</div>

<script>
function ckTemPermissao(cat){try{const c=JSON.parse(document.cookie.split(';').find(c=>c.trim().startsWith('ck_consent='))?.split('=')?.[1]||'{}');return!!c[cat]}catch{return false}}
function acceptCookies(t){const d={necessary:1,analytics:t==='all'?1:0,marketing:t==='all'?1:0,preferences:t==='all'?1:0};saveCookieConsent(d)}
function openCookieSettings(){document.getElementById('cookieModal').style.display='flex'}
function saveCookieSettings(){const d={necessary:1,analytics:document.getElementById('ckAnalytics').checked?1:0,marketing:document.getElementById('ckMarketing').checked?1:0,preferences:document.getElementById('ckPreferences').checked?1:0};saveCookieConsent(d);document.getElementById('cookieModal').style.display='none'}
function saveCookieConsent(d){document.cookie='ck_consent='+JSON.stringify(d)+';path=/;max-age=31536000;SameSite=Lax';document.getElementById('cookieBanner').style.display='none';fetch('/cookies/consent',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]')?.content||''},body:JSON.stringify(d)})}
if(!document.cookie.includes('ck_consent=')){document.getElementById('cookieBanner').style.display='flex'}
</script>
