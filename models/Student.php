<?php
require_once __DIR__ . '/../config/database.php';

class Student
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = (new Database())->connect();
    }

    public function all(): array
    {
        return $this->pdo->query('SELECT * FROM alunos ORDER BY id DESC')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM alunos WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $student = $stmt->fetch();
        return $student ?: null;
    }

    public function create(string $nome, string $email, int $idade, string $curso): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO alunos (nome, email, idade, curso) VALUES (:nome, :email, :idade, :curso)');
        return $stmt->execute(compact('nome', 'email', 'idade', 'curso'));
    }

    public function update(int $id, string $nome, string $email, int $idade, string $curso): bool
    {
        $stmt = $this->pdo->prepare('UPDATE alunos SET nome = :nome, email = :email, idade = :idade, curso = :curso WHERE id = :id');
        return $stmt->execute(compact('id', 'nome', 'email', 'idade', 'curso'));
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM alunos WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public function count(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM alunos')->fetchColumn();
    }
}
