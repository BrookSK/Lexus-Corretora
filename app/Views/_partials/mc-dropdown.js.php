<style>
.mc-wrap { position: relative; }
.mc-toggle {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: var(--bg-input, var(--bg-card));
  border: 1px solid var(--border-color);
  border-radius: 4px;
  padding: 8px 12px;
  cursor: pointer;
  font-size: .85rem;
  color: var(--text-primary);
  text-align: left;
}
.mc-toggle:hover { border-color: var(--gold); }
.mc-panel {
  display: none;
  position: absolute;
  top: calc(100% + 4px);
  left: 0; right: 0;
  background: var(--bg-card);
  border: 1px solid var(--border-color);
  border-radius: 4px;
  z-index: 100;
  box-shadow: 0 8px 24px rgba(0,0,0,.3);
}
.mc-panel.open { display: block; }
.mc-search {
  width: 100%;
  border: none;
  border-bottom: 1px solid var(--border-color);
  background: transparent;
  padding: 8px 12px;
  font-size: .82rem;
  color: var(--text-primary);
  outline: none;
  box-sizing: border-box;
}
.mc-list { max-height: 220px; overflow-y: auto; padding: 4px 0; }
.mc-item {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 7px 14px;
  font-size: .83rem;
  color: var(--text-primary);
  cursor: pointer;
  user-select: none;
}
.mc-item:hover { background: rgba(184,148,90,.08); }
.mc-item input[type=checkbox] { accent-color: var(--gold); width: 14px; height: 14px; flex-shrink: 0; }
.mc-item.mc-hidden { display: none; }
</style>
<script>
function mcOpen(id) {
  var panel = document.getElementById(id + '-panel');
  var isOpen = panel.classList.contains('open');
  document.querySelectorAll('.mc-panel.open').forEach(function(p){ p.classList.remove('open'); });
  if (!isOpen) {
    panel.classList.add('open');
    var s = panel.querySelector('.mc-search');
    if (s) { s.value = ''; mcFilter(id, ''); s.focus(); }
  }
}
function mcUpdate(id) {
  var checked = document.querySelectorAll('#' + id + ' input[type=checkbox]:checked');
  var lbl = document.getElementById(id + '-lbl');
  if (lbl) lbl.textContent = checked.length ? checked.length + ' selecionada(s)' : 'Selecione especialidades';
}
function mcFilter(id, q) {
  q = (q || '').toLowerCase();
  document.querySelectorAll('#' + id + '-list .mc-item').forEach(function(item) {
    item.classList.toggle('mc-hidden', q !== '' && item.textContent.toLowerCase().indexOf(q) === -1);
  });
}
document.addEventListener('click', function(e) {
  if (!e.target.closest('.mc-wrap')) {
    document.querySelectorAll('.mc-panel.open').forEach(function(p){ p.classList.remove('open'); });
  }
});
</script>
