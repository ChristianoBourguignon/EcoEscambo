<!-- Modal Login/Cadastro -->
<div class="modal fade" id="perfilModal" tabindex="-1" aria-labelledby="perfilModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-4">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="perfilModalLabel">Acesse sua conta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <!-- Abas -->
                <ul class="nav nav-tabs" id="loginTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab">Login</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab">Cadastro</button>
                    </li>
                </ul>

                <!-- Conteúdo das Abas -->
                <div class="tab-content" id="loginTabContent">
                    <!-- Login -->
                    <div class="tab-pane fade show active p-4" id="login" role="tabpanel">
                        <form action="backend/login.php" method="POST">
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

                    <!-- Cadastro -->
                    <div class="tab-pane fade p-4" id="register" role="tabpanel">
                        <form action="backend/register.php" method="POST">
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
                            <button type="submit" class="btn btn-success">Cadastrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
