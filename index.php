<?php
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/controllers/LoginController.php';
require_once __DIR__ . '/controllers/StudentController.php';

if (session_status() === PHP_SESSION_NONE) session_start();
$router = new Router();
$login = new LoginController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    if ($acao === 'login') {
        $r = $login->login($_POST['email'] ?? '', $_POST['senha'] ?? '');
        $_SESSION['mensagem'] = $r['mensagem']; $_SESSION['tipo_mensagem'] = $r['sucesso'] ? 'success' : 'error';
        $router->redirect('index.php');
    }
    if ($acao === 'cadastro') {
        $r = $login->register($_POST['nome'] ?? '', $_POST['email'] ?? '', $_POST['senha'] ?? '');
        $_SESSION['mensagem'] = $r['mensagem']; $_SESSION['tipo_mensagem'] = $r['sucesso'] ? 'success' : 'error';
        $router->redirect('index.php');
    }
    if ($acao === 'logout') { $login->logout(); $router->redirect('index.php'); }

    $router->requireLogin();
    $students = new StudentController();
    if ($acao === 'salvar_aluno') {
        $r = $students->save($_POST); $_SESSION['mensagem'] = $r['mensagem']; $_SESSION['tipo_mensagem'] = $r['sucesso'] ? 'success' : 'error';
        $router->redirect('index.php?pagina=alunos');
    }
    if ($acao === 'excluir_aluno') {
        $r = $students->delete((int)($_POST['id'] ?? 0)); $_SESSION['mensagem'] = $r['mensagem']; $_SESSION['tipo_mensagem'] = $r['sucesso'] ? 'success' : 'error';
        $router->redirect('index.php?pagina=alunos');
    }
}

if (!$login->isLogged()) { require __DIR__ . '/views/login.php'; exit; }
$router->requireLogin();
$pagina = $_GET['pagina'] ?? 'dashboard';
$students = new StudentController();
$alunos = $students->all();
$alunosCount = $students->count();
$usuariosCount = $login->userCount();

if ($pagina === 'alunos') { require __DIR__ . '/views/alunos.php'; exit; }
if ($pagina === 'novo') { $aluno = null; require __DIR__ . '/views/editar.php'; exit; }
if ($pagina === 'editar') { $aluno = $students->find((int)($_GET['id'] ?? 0)); if (!$aluno) $router->redirect('index.php?pagina=alunos'); require __DIR__ . '/views/editar.php'; exit; }
?>
<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><title>Dashboard | Alunos+</title><link rel="stylesheet" href="assets/style.css"></head><body>
<div class="app"><aside class="sidebar"><div class="brand"><div class="brand-icon">A</div><span>Alunos+</span></div><div class="nav-title">Menu principal</div><nav class="nav"><a class="active" href="index.php?pagina=dashboard">⌂ <span>Dashboard</span></a><a href="index.php?pagina=alunos">▦ <span>Alunos</span></a></nav><div class="nav-title" style="margin-top:25px">Conta</div><nav class="nav"><a href="#" onclick="document.getElementById('logout').submit();return false">↪ <span>Sair</span></a></nav><form id="logout" method="POST" style="display:none"><input name="acao" value="logout"></form></aside>
<main class="main"><div class="topbar"><div><h1>Dashboard</h1><div class="muted">Visão geral do seu sistema.</div></div><div class="user"><div class="avatar"><?= strtoupper(substr($_SESSION['usuario_nome'],0,1)) ?></div><div><strong><?= htmlspecialchars($_SESSION['usuario_nome']) ?></strong><div class="small muted">Administrador</div></div></div></div>
<div class="cards"><div class="stat"><div class="iconbox">🎓</div><div class="label">Alunos cadastrados</div><div class="value"><?= $alunosCount ?></div></div><div class="stat"><div class="iconbox">👥</div><div class="label">Usuários do sistema</div><div class="value"><?= $usuariosCount ?></div></div><div class="stat"><div class="iconbox">✓</div><div class="label">Status</div><div class="value" style="font-size:22px;color:var(--success)">Online</div></div></div>
<div class="card"><div class="card-head"><div><h2>Alunos recentes</h2><div class="muted small">Últimos registros cadastrados</div></div><a class="btn" href="index.php?pagina=novo">+ Cadastrar aluno</a></div><div class="table-wrap"><table><thead><tr><th>Nome</th><th>Curso</th><th>Idade</th><th>Status</th></tr></thead><tbody><?php foreach(array_slice($alunos,0,5) as $a): ?><tr><td><strong><?= htmlspecialchars($a['nome']) ?></strong><div class="small muted"><?= htmlspecialchars($a['email']) ?></div></td><td><?= htmlspecialchars($a['curso']) ?></td><td><?= (int)$a['idade'] ?></td><td><span class="badge">Ativo</span></td></tr><?php endforeach; ?><?php if(!$alunos): ?><tr><td colspan="4" class="empty">Nenhum aluno cadastrado ainda.</td></tr><?php endif; ?></tbody></table></div></div>
</main></div></body></html>
