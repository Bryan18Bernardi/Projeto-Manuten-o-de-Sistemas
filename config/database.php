<?php

class Database
{
    private string $host = 'localhost';
    private string $dbName = 'sistema_alunos';
    private string $username = 'root';
    private string $password = '';

    public function connect(): PDO
    {
        try {
            return new PDO(
                "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            die('Não foi possível conectar ao banco de dados. Verifique o XAMPP e as configurações em config/database.php.');
        }
    }
}
