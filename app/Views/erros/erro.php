<?php
declare(strict_types=1);
use LEX\Core\View;
$codigo = $codigo ?? 500;
$mensagem = $mensagem ?? 'Erro interno do servidor.';
$errorId = $errorId ?? null;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Erro <?php echo $codigo; ?> — Lexus</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;600&family=Outfit:wght@300;400;500&display=swap" rel="stylesheet"/>
<style>
:root{--black:#0C0C0A;--white:#F5F2ED;--gold:#B8945A}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Outfit',sans-serif;background:var(--black);color:var(--white);min-height:100vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:40px}
.code{font-family:'Cormorant Garamond',serif;font-size:8rem;font-weight:300;color:var(--gold);line-height:1}
.msg{font-size:1rem;color:rgba(245,242,237,.5);margin:20px 0 32px;max-width:400px}
.eid{font-size:.72rem;color:rgba(245,242,237,.25);margin-top:16px}
a{color:var(--gold);text-decoration:none;font-size:.8rem;letter-spacing:.12em;text-transform:uppercase}
a:hover{color:#D4AD75}
</style>
</head>
<body>
<div>
  <div class="code"><?php echo $codigo; ?></div>
  <p class="msg"><?php echo View::e($mensagem); ?></p>
  <a href="/">← Voltar ao início</a>
  <?php if ($errorId): ?>
  <p class="eid">ID: <?php echo View::e($errorId); ?></p>
  <?php endif; ?>
</div>
</body></html>
