<?php
declare(strict_types=1);
use LEX\Core\{View, I18n};
?>
<div id="chatbotWidget" class="chatbot-widget">
  <button id="chatbotToggle" class="chatbot-toggle" aria-label="Chat">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
  </button>
  <div id="chatbotPanel" class="chatbot-panel" style="display:none">
    <div class="chatbot-header">
      <span class="chatbot-logo">Lexus</span>
      <button id="chatbotClose" class="chatbot-close" aria-label="Fechar">&times;</button>
    </div>
    <div id="chatbotBody" class="chatbot-body">
      <div class="chatbot-msg bot"><?php echo View::e(I18n::t('chatbot.titulo')); ?></div>
      <div class="chatbot-options" id="chatbotMenu">
        <button class="chatbot-opt" data-key="q1"><?php echo View::e(I18n::t('chatbot.opcao1')); ?></button>
        <button class="chatbot-opt" data-key="q2"><?php echo View::e(I18n::t('chatbot.opcao2')); ?></button>
        <button class="chatbot-opt" data-key="q3"><?php echo View::e(I18n::t('chatbot.opcao3')); ?></button>
        <button class="chatbot-opt" data-key="q4"><?php echo View::e(I18n::t('chatbot.opcao4')); ?></button>
        <button class="chatbot-opt" data-key="q5"><?php echo View::e(I18n::t('chatbot.opcao5')); ?></button>
        <button class="chatbot-opt" data-key="q6"><?php echo View::e(I18n::t('chatbot.opcao6')); ?></button>
      </div>
    </div>
  </div>
</div>

<script>
(function(){
  const toggle=document.getElementById('chatbotToggle'),panel=document.getElementById('chatbotPanel'),close=document.getElementById('chatbotClose'),body=document.getElementById('chatbotBody');
  const answers={
    q1:'A Lexus é uma corretora estratégica de obras e reformas. Conectamos clientes a parceiros qualificados, estruturando demandas e facilitando a seleção das melhores propostas.',
    q2:'Estruturamos sua demanda, conectamos com parceiros certificados, coletamos propostas e auxiliamos na decisão. O contrato é direto entre você e o parceiro.',
    q3:'Acesse <a href="/abrir-demanda">Abrir Demanda</a> e preencha o formulário com os detalhes do seu projeto. Nossa equipe entrará em contato.',
    q4:'Acesse <a href="/seja-parceiro">Seja Parceiro</a> e cadastre sua empresa. Após análise, você poderá receber oportunidades qualificadas.',
    q5:'O Selo Vetriks certifica parceiros que passaram por nosso processo rigoroso de qualificação em experiência, capacidade e confiabilidade.',
    q6:'Entre em contato pelo <a href="/contato">formulário</a> ou envie um e-mail para contato@lexuscorretora.com.br.'
  };
  toggle.addEventListener('click',()=>{panel.style.display=panel.style.display==='none'?'flex':'none'});
  close.addEventListener('click',()=>{panel.style.display='none'});
  body.addEventListener('click',e=>{
    const btn=e.target.closest('.chatbot-opt');
    if(!btn)return;
    const key=btn.dataset.key;
    if(answers[key]){
      const msg=document.createElement('div');msg.className='chatbot-msg bot';msg.innerHTML=answers[key];
      const back=document.createElement('button');back.className='chatbot-opt chatbot-back';back.textContent='<?php echo View::e(I18n::t('chatbot.voltar')); ?>';
      back.addEventListener('click',()=>{document.getElementById('chatbotMenu').style.display='flex';msg.remove();back.remove()});
      document.getElementById('chatbotMenu').style.display='none';
      body.appendChild(msg);body.appendChild(back);body.scrollTop=body.scrollHeight;
    }
  });
})();
</script>
