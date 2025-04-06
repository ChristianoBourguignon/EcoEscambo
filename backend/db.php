<?php
$dbServer = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "ecoescambo";

$link = mysqli_connect($dbServer, $dbUsername, $dbPassword);
if (!$link) {
    die('Falha na conexão: ' . mysql_error());
}
$sql = "CREATE DATABASE IF NOT EXISTS ${dbName}";

if (mysqli_query($link,$sql)) {
    echo `<script>console.log("Conectado com sucesso");</script>`;
} else {
    echo `<script>console.log("Erro ao Conectar");</script>`;
    echo mysql_error();
}

$conn = new mysqli($dbServer, $dbUsername, $dbPassword,$dbName);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
echo `<script>console.log('Conectado com sucesso');</script>`;