// Máscara e validação de CNPJ
function mascaraCNPJ(input) {
  input.addEventListener('input', function(e) {
    var v = e.target.value.replace(/\D/g, '').substring(0, 14);
    if (v.length > 12) v = v.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{1,2})/, '$1.$2.$3/$4-$5');
    else if (v.length > 8) v = v.replace(/^(\d{2})(\d{3})(\d{3})(\d{1,4})/, '$1.$2.$3/$4');
    else if (v.length > 5) v = v.replace(/^(\d{2})(\d{3})(\d{1,3})/, '$1.$2.$3');
    else if (v.length > 2) v = v.replace(/^(\d{2})(\d{1,3})/, '$1.$2');
    e.target.value = v;
  });
}

function validarCNPJ(cnpj) {
  cnpj = cnpj.replace(/\D/g, '');
  if (cnpj.length !== 14) return false;
  if (/^(\d)\1{13}$/.test(cnpj)) return false;
  var t = cnpj.length - 2, d = cnpj.substring(t), p1 = 0, p2 = 0, pos = t - 7;
  for (var i = t; i >= 1; i--) {
    p1 += cnpj.charAt(t - i) * pos--;
    if (pos < 2) pos = 9;
  }
  var r = p1 % 11 < 2 ? 0 : 11 - p1 % 11;
  if (r != d.charAt(0)) return false;
  t++; p2 = 0; pos = t - 7;
  for (var i = t; i >= 1; i--) {
    p2 += cnpj.charAt(t - i) * pos--;
    if (pos < 2) pos = 9;
  }
  r = p2 % 11 < 2 ? 0 : 11 - p2 % 11;
  return r == d.charAt(1);
}

// Auto-aplicar em campos com class="cnpj-input"
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.cnpj-input').forEach(function(el) {
    mascaraCNPJ(el);
  });
});
