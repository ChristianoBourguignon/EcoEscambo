<?php

namespace app\controllers;
use PDO;
class dbController
{
    private static $host = 'localhost';
    private static $dbname = 'ecoescambo';
    private static $username = 'root';
    private static $password = '';
    private static $pdo;

    public static function getPdo(): PDO {
        return self::$pdo;
    }

    public static function getConnection()
    {
        if (!self::$pdo) {
            try {
                // Conecta ao servidor para criar o banco, se necessário
                $pdoTemp = new PDO("mysql:host=" . self::$host, self::$username, self::$password);
                $pdoTemp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdoTemp->exec("CREATE DATABASE IF NOT EXISTS " . self::$dbname . " CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");

                // Conecta agora ao banco criado
                self::$pdo = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=utf8", self::$username, self::$password);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Criação das tabelas
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
                        fk_categoria VARCHAR(255) NOT NULL,
                        img VARCHAR(255) NOT NULL,
                        idUser INT NOT NULL,
                        FOREIGN KEY (idUser) REFERENCES users(id),
                        FOREIGN KEY (fk_categoria) REFERENCES categorias(nome)
                    );
                ";

                $sqlTroca = "
                    CREATE TABLE IF NOT EXISTS troca (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        idUserDesejado INT NOT NULL,
                        idUser INT NOT NULL,
                        idProdDesejado INT NOT NULL,
                        idProdUser INT NOT NULL,
                        Status INT NOT NULL DEFAULT '0',
                        FOREIGN KEY (idUserDesejado) REFERENCES users(id),
                        FOREIGN KEY (idProdDesejado) REFERENCES produtos(id),
                        FOREIGN KEY (idUser) REFERENCES users(id),
                        FOREIGN KEY (idProdUser) REFERENCES produtos(id)
                    );
                ";

                $sqlCategoria = "
                    CREATE TABLE IF NOT EXISTS categorias (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        nome VARCHAR(255) NOT NULL UNIQUE
                    );
                ";


                // Executa as queries
                self::$pdo->exec($sqlUsers);
                self::$pdo->exec($sqlCategoria);
                self::$pdo->exec($sqlProdutos);
                self::$pdo->exec($sqlTroca);

            } catch (PDOException $e) {
                $_SESSION['modal'] = [
                    'msg' =>'Erro na conexão do banco de dados: ' . $e->getMessage(),
                    'statuscode' => 404
                ];
                header("location: " . BASE);
            }
        }

        return self::$pdo;
    }
}
