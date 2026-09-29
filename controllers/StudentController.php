<?php
require_once __DIR__ . '/../models/Student.php';

class StudentController
{
    private Student $model;

    public function __construct() { $this->model = new Student(); }

    public function all(): array { return $this->model->all(); }
    public function find(int $id): ?array { return $this->model->find($id); }
    public function count(): int { return $this->model->count(); }

    public function save(array $data): array
    {
        $nome = trim($data['nome'] ?? '');
        $email = trim($data['email'] ?? '');
        $idade = filter_var($data['idade'] ?? null, FILTER_VALIDATE_INT);
        $curso = trim($data['curso'] ?? '');

        if ($nome === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $idade === false || $idade < 1 || $idade > 120 || $curso === '') {
            return ['sucesso' => false, 'mensagem' => 'Preencha todos os campos corretamente.'];
        }

        try {
            if (!empty($data['id'])) {
                $ok = $this->model->update((int)$data['id'], $nome, $email, $idade, $curso);
                return ['sucesso' => $ok, 'mensagem' => $ok ? 'Aluno atualizado com sucesso.' : 'Não foi possível atualizar.'];
            }
            $ok = $this->model->create($nome, $email, $idade, $curso);
            return ['sucesso' => $ok, 'mensagem' => $ok ? 'Aluno cadastrado com sucesso.' : 'Não foi possível cadastrar.'];
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') return ['sucesso' => false, 'mensagem' => 'Este e-mail já está cadastrado.'];
            return ['sucesso' => false, 'mensagem' => 'Erro ao salvar o aluno.'];
        }
    }

    public function delete(int $id): array
    {
        try {
            return ['sucesso' => $this->model->delete($id), 'mensagem' => 'Aluno excluído com sucesso.'];
        } catch (PDOException $e) {
            return ['sucesso' => false, 'mensagem' => 'Não foi possível excluir o aluno.'];
        }
    }
}
