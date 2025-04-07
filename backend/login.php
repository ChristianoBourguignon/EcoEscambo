<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (empty($email) || empty($senha)) {
        echo "Preencha todos os campos!";
        exit;
    }

    try {
        $pdo = Db::getConnection();

        $stmt = $pdo->prepare("SELECT id, nome, email, senha FROM users WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Sem criptografia, comparação direta
        if ($usuario && $usuario['senha'] === $senha) {
            // Armazenar informações importantes na sessão
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['usuario_nome'] = $usuario['nome']; // <- ESSENCIAL para mostrar o nome

            header("Location: ../dashboard.php");
            exit;
        } else {
            echo "Email ou senha incorretos.";
            exit;
        }
    } catch (PDOException $e) {
        echo "Erro de conexão: " . $e->getMessage();
        exit;
    }
} else {
    echo "Requisição inválida.";
    exit;
}
