<?php
$dbServer = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "ecoescambo";

$link = mysqli_connect($dbServer, $dbUsername, $dbPassword);
if (!$link) {
    die('Falha na conexão: ' . mysql_error());
}

$sqlCreateDb = "CREATE DATABASE IF NOT EXISTS ${dbName}";
$sqlCreateTableUser = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email varchar(255) NOT NULL,
    senha VARCHAR(255) NOT NULL
);";

$sqlCreateTableProd = "
CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao VARCHAR(255) NOT NULL,
    idUser INT NOT NULL,
    FOREIGN KEY (idUser) REFERENCES users(id)
);";
if (mysqli_query($link,$sqlCreateDb)) {
    echo `<script>console.log("Conectado com sucesso");</script>`;
} else {
    echo `<script>console.log("Erro ao Conectar");</script>`;
    echo mysql_error();
}

$conn = new mysqli($dbServer, $dbUsername, $dbPassword,$dbName);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
} else {
    $conn->query($sqlCreateTableUser);
    $conn->query($sqlCreateTableProd);
}