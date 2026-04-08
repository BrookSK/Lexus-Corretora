/**
 * CRUD Actions - Funções reutilizáveis para ações de CRUD
 * Lexus Corretora - 2024
 */

/**
 * Excluir registro com confirmação
 * @param {string} url - URL da API de exclusão
 * @param {string} entityName - Nome da entidade (ex: "cliente", "demanda")
 * @param {function} onSuccess - Callback de sucesso (opcional)
 */
function excluirRegistro(url, entityName, onSuccess) {
  if (!confirm(`Tem certeza que deseja excluir este ${entityName}? Esta ação não pode ser desfeita.`)) {
    return;
  }

  // Obter CSRF token
  const csrfToken = document.querySelector('input[name="_csrf_token"]')?.value || 
                    document.querySelector('meta[name="csrf-token"]')?.content;

  fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-Token': csrfToken || ''
    },
    body: JSON.stringify({})
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Mostrar mensagem de sucesso
      mostrarFlash('success', data.message || `${entityName} excluído com sucesso!`);
      
      // Executar callback ou recarregar página
      if (typeof onSuccess === 'function') {
        onSuccess();
      } else {
        setTimeout(() => {
          window.location.reload();
        }, 1000);
      }
    } else {
      mostrarFlash('error', data.message || `Erro ao excluir ${entityName}`);
    }
  })
  .catch(error => {
    console.error('Erro:', error);
    mostrarFlash('error', `Erro ao excluir ${entityName}: ${error.message}`);
  });
}

/**
 * Mostrar mensagem flash
 * @param {string} type - Tipo da mensagem (success, error, warning, info)
 * @param {string} message - Mensagem a ser exibida
 */
function mostrarFlash(type, message) {
  // Remover flash existente
  const existingFlash = document.querySelector('.flash-message');
  if (existingFlash) {
    existingFlash.remove();
  }

  // Criar novo flash
  const flash = document.createElement('div');
  flash.className = `flash-message flash-${type}`;
  flash.style.cssText = `
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 16px 24px;
    background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#f59e0b'};
    color: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 10000;
    animation: slideInRight 0.3s ease-out;
    max-width: 400px;
    font-size: 14px;
    line-height: 1.5;
  `;
  flash.textContent = message;

  // Adicionar animação
  const style = document.createElement('style');
  style.textContent = `
    @keyframes slideInRight {
      from {
        transform: translateX(100%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }
    @keyframes slideOutRight {
      from {
        transform: translateX(0);
        opacity: 1;
      }
      to {
        transform: translateX(100%);
        opacity: 0;
      }
    }
  `;
  if (!document.querySelector('#flash-animations')) {
    style.id = 'flash-animations';
    document.head.appendChild(style);
  }

  document.body.appendChild(flash);

  // Remover após 5 segundos
  setTimeout(() => {
    flash.style.animation = 'slideOutRight 0.3s ease-out';
    setTimeout(() => flash.remove(), 300);
  }, 5000);
}

/**
 * Adicionar botão de exclusão em uma linha de tabela
 * @param {string} selector - Seletor CSS da tabela
 * @param {string} urlPattern - Padrão da URL (ex: "/equipe/clientes/{id}/excluir")
 * @param {string} entityName - Nome da entidade
 */
function adicionarBotoesExcluir(selector, urlPattern, entityName) {
  const tabela = document.querySelector(selector);
  if (!tabela) return;

  const linhas = tabela.querySelectorAll('tbody tr');
  
  linhas.forEach(linha => {
    const id = linha.dataset.id;
    if (!id) return;

    // Verificar se já tem botão de excluir
    const acoesCell = linha.querySelector('td:last-child');
    if (!acoesCell || acoesCell.querySelector('.btn-excluir')) return;

    // Criar botão de exclusão
    const btnExcluir = document.createElement('button');
    btnExcluir.className = 'btn btn-danger btn-sm btn-excluir';
    btnExcluir.innerHTML = '🗑️ Excluir';
    btnExcluir.style.cssText = 'margin-left: 8px; padding: 4px 12px; font-size: 0.85rem;';
    btnExcluir.onclick = function(e) {
      e.preventDefault();
      e.stopPropagation();
      const url = urlPattern.replace('{id}', id);
      excluirRegistro(url, entityName);
    };

    acoesCell.appendChild(btnExcluir);
  });
}
