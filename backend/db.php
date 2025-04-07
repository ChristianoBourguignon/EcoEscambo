<?php

class Db {
    private static $host = 'localhost';
    private static $dbname = 'ecoescambo';
    private static $username = 'root';
    private static $password = '';
    private static $pdo;

    public static function getConnection() {
        if (!self::$pdo) {
            try {
                // Conecta ao servidor para criar o banco, se necessário
                $pdoTemp = new PDO("mysql:host=" . self::$host, self::$username, self::$password);
                $pdoTemp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdoTemp->exec("CREATE DATABASE IF NOT EXISTS " . self::$dbname);
                
                // Conecta agora ao banco criado
                self::$pdo = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=utf8", self::$username, self::$password);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Criação das tabelas, se não existirem
                $sqlUsers = "
                    CREATE TABLE IF NOT EXISTS users (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        nome VARCHAR(100) NOT NULL,
                        email VARCHAR(255) NOT NULL,
                        senha VARCHAR(255) NOT NULL
                    );
                ";

                $sqlProdutos = "
                    CREATE TABLE IF NOT EXISTS produtos (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        nome VARCHAR(100) NOT NULL,
                        descricao VARCHAR(255) NOT NULL,
                        img VARCHAR(255) NOT NULL,
                        idUser INT NOT NULL,
                        FOREIGN KEY (idUser) REFERENCES users(id)
                    );
                ";

                self::$pdo->exec($sqlUsers);
                self::$pdo->exec($sqlProdutos);
            } catch (PDOException $e) {
                die("Erro na conexão ou criação do banco: " . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}
