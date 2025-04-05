<?php include("models/header.php"); ?>
<div class="container mt-5 w-50 p-5 shadow-lg rounded">
    <ul class="nav nav-tabs" id="loginTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab">Login</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab">Cadastro</button>
        </li>
    </ul>
    <div class="tab-content" id="loginTabContent">
        <!-- Aba Login -->
        <div class="tab-pane fade show active p-4" id="login" role="tabpanel">
            <form action="../actions/login.php" method="POST">
                <div class="mb-3">
                    <label for="loginEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="loginEmail" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="loginPassword" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="loginPassword" name="senha" required>
                </div>
                <button type="submit" class="btn btn-success">Entrar</button>
            </form>
        </div>

        <!-- Aba Cadastro -->
        <div class="tab-pane fade p-4" id="register" role="tabpanel">
            <form action="../actions/register.php" method="POST">
                <div class="mb-3">
                    <label for="registerName" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="registerName" name="nome" required>
                </div>
                <div class="mb-3">
                    <label for="registerEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="registerEmail" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="registerPassword" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="registerPassword" name="senha" required>
                </div>
                <button type="submit" class="btn btn-primary">Cadastrar</button>
            </form>
        </div>
    </div>
</div>
<?php include("models/footer.php"); ?>
