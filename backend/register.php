<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (empty($nome) || empty($email) || empty($senha)) {
        echo "Preencha todos os campos!";
        exit;
    }

    try {
        $pdo = Db::getConnection();

        // Verifica se já existe um usuário com o mesmo email
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->fetch()) {
            echo "Já existe um usuário com este e-mail.";
            exit;
        }

        // Insere o novo usuário (sem hash, como combinado)
        $stmt = $pdo->prepare("INSERT INTO users (nome, email, senha) VALUES (:nome, :email, :senha)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->execute();

        $_SESSION['usuario_id'] = $pdo->lastInsertId();
        $_SESSION['usuario_email'] = $email;

        header("Location: ../dashboard.php");
        exit;
    } catch (PDOException $e) {
        echo "Erro ao registrar: " . $e->getMessage();
        exit;
    }
} else {
    echo "Requisição inválida.";
    exit;
}
