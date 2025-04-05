<?php
$dbServer = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "ecoescambo";

$conn = new mysqli($dbServer, $dbUsername, $dbPassword,$dbName);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
echo "Conectado com sucesso";