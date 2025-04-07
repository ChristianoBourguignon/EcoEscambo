<?php
session_start();
require_once 'backend/db.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.html"); // redireciona para login
    exit;
}

// Conecta ao banco e busca o nome do usuário
try {
    $pdo = Db::getConnection();
    $stmt = $pdo->prepare("SELECT nome FROM users WHERE id = :id");
    $stmt->bindParam(':id', $_SESSION['usuario_id']);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    $nomeUsuario = $usuario ? $usuario['nome'] : 'Usuário';
    $_SESSION['usuario_nome'] = $nomeUsuario; // Salva na sessão para o header
} catch (PDOException $e) {
    echo "Erro ao carregar dados do usuário: " . $e->getMessage();
    exit;
}

require_once("models/header.php");
?>

<div class="container mt-4">
    <h1>Olá, <?= htmlspecialchars($nomeUsuario) ?>!</h1>

    <h2 class="mt-5">Produtos</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
            </tr>
        </thead>
        <tbody>
            <!-- Aqui você pode listar produtos dinamicamente depois -->
            <tr>
                <td colspan="3" class="text-center">Nenhum produto cadastrado ainda.</td>
            </tr>
        </tbody>
    </table>
</div>

<?php 
   require_once("models/footer.php"); 
?>
