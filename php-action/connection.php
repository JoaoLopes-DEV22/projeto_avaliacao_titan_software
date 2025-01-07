<?php

// Classe responsável para conectar ao banco de dados
class Database
{
    // Credenciais privadas para conectar ao banco de dados
    private $host = "localhost";
    private $db_name = "db_titan_software";
    private $username = "root";
    private $password = "";
    private $conn;

    // Função para testar conexão ao banco de dados
    public function getConnection(): ?PDO
    {
        $this->conn = null;

        // Tenta fazer a conexão
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log de erro caso não consiga se conectar
            error_log("Erro na conexão com o banco de dados: " . $e->getMessage());
        }
        
        // Retorna se a conexão foi sucedida ou não
        return $this->conn;
    }
}
