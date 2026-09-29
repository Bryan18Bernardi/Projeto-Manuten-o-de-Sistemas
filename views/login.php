<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$mensagem = $_SESSION['mensagem'] ?? '';
$tipo = $_SESSION['tipo_mensagem'] ?? 'error';
unset($_SESSION['mensagem'], $_SESSION['tipo_mensagem']);
?>
<!DOCTYPE html>
<html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><title>Alunos | Acesso</title><link rel="stylesheet" href="assets/style.css"></head>
<body>
<div class="login-page">
    <section class="login-hero">
        <div class="hero-content">
            <div class="brand"><div class="brand-icon">A</div><span>Alunos+</span></div>
            <h1>Gestão de alunos simples e organizada.</h1>
            <p>Um painel para cadastrar, consultar e administrar os alunos da sua instituição em um só lugar.</p>
            <div class="features"><div class="feature">✓ Cadastro rápido</div><div class="feature">✓ Dados organizados</div><div class="feature">✓ Acesso protegido</div><div class="feature">✓ Interface responsiva</div></div>
        </div>
    </section>
    <section class="login-side">
        <div class="login-card">
            <h2>Bem-vindo 👋</h2><p class="muted">Entre na sua conta ou crie um acesso.</p>
            <?php if($mensagem): ?><div class="notice <?= $tipo === 'success' ? 'success' : 'error' ?>"><?= htmlspecialchars($mensagem) ?></div><?php endif; ?>
            <div class="tabs"><div class="tab active" data-tab="login">Entrar</div><div class="tab" data-tab="cadastro">Criar conta</div></div>
            <form id="login" method="POST" action="index.php">
                <input type="hidden" name="acao" value="login">
                <div class="field"><label>E-mail</label><input type="email" name="email" placeholder="voce@email.com" required></div>
                <div class="field"><label>Senha</label><input type="password" name="senha" placeholder="Sua senha" required></div>
                <button class="btn" type="submit">Entrar no sistema</button>
                <p class="muted small" style="text-align:center;margin-top:16px">Acesso inicial: admin@sistema.com / admin123</p>
            </form>
            <form id="cadastro" class="hidden" method="POST" action="index.php">
                <input type="hidden" name="acao" value="cadastro">
                <div class="field"><label>Nome completo</label><input type="text" name="nome" maxlength="100" placeholder="Seu nome" required></div>
                <div class="field"><label>E-mail</label><input type="email" name="email" maxlength="100" placeholder="voce@email.com" required></div>
                <div class="field"><label>Senha</label><input type="password" name="senha" minlength="6" placeholder="Mínimo 6 caracteres" required></div>
                <button class="btn" type="submit">Criar minha conta</button>
            </form>
        </div>
    </section>
</div>
<script>document.querySelectorAll('.tab').forEach(t=>t.onclick=()=>{document.querySelectorAll('.tab').forEach(x=>x.classList.remove('active'));document.querySelectorAll('form[id]').forEach(x=>x.classList.add('hidden'));t.classList.add('active');document.getElementById(t.dataset.tab).classList.remove('hidden')});</script>
</body></html>
