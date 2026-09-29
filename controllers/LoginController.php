<?php
require_once __DIR__ . '/../models/User.php';

class LoginController
{
    private User $userModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->userModel = new User();
    }

    public function login(string $email, string $senha): array
    {
        $email = trim($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $senha === '') {
            return ['sucesso' => false, 'mensagem' => 'Informe um e-mail válido e sua senha.'];
        }

        $usuario = $this->userModel->findByEmail($email);
        if (!$usuario || !password_verify($senha, $usuario['senha'])) {
            return ['sucesso' => false, 'mensagem' => 'E-mail ou senha incorretos.'];
        }

        session_regenerate_id(true);
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_email'] = $usuario['email'];
        return ['sucesso' => true, 'mensagem' => 'Login realizado com sucesso.'];
    }

    public function register(string $nome, string $email, string $senha): array
    {
        $nome = trim($nome); $email = trim($email);
        if ($nome === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($senha) < 6) {
            return ['sucesso' => false, 'mensagem' => 'Preencha os dados corretamente. A senha deve ter pelo menos 6 caracteres.'];
        }
        if ($this->userModel->emailExists($email)) {
            return ['sucesso' => false, 'mensagem' => 'Este e-mail já está cadastrado.'];
        }
        try {
            $this->userModel->create($nome, $email, $senha);
            return ['sucesso' => true, 'mensagem' => 'Conta criada. Agora você pode entrar.'];
        } catch (PDOException $e) {
            return ['sucesso' => false, 'mensagem' => 'Não foi possível concluir o cadastro.'];
        }
    }

    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }

    public function isLogged(): bool { return isset($_SESSION['usuario_id']); }
    public function users(): array { return $this->userModel->all(); }
    public function userCount(): int { return $this->userModel->count(); }
}
