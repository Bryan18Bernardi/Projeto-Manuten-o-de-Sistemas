<?php
class Router
{
    public function redirect(string $url): never
    {
        header("Location: $url"); exit;
    }

    public function requireLogin(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['usuario_id'])) $this->redirect('index.php');
    }
}
