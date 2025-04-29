# EcoEscambo

## Descrição
EcoEscambo é uma plataforma web para troca de produtos, promovendo uma troca sem uso de dinheiro.

## Estrutura do Projeto

### Diretórios Principais

#### `/backend`
- `db.php` - Configuração da conexão com o banco de dados
- `login.php` - Processamento de autenticação
- `register.php` - Processamento de cadastro de usuários
- `processarProduto.php` - Processamento de cadastro de produtos
- `AlterarProduto.php` - Processamento de alteração de produtos
- `ExcluirProduto.php` - Processamento de exclusão de produtos
- `operacaoTroca.php` - Processamento de operações de troca
- `recuperarSenha.php` - Processamento de recuperação de senha
- `TrocarProduto.php` - Processamento de troca de produtos
- `logout.php` - Processamento de logout

#### `/models`
- `navbar.php` - Componente de navegação
- `header.php` - Cabeçalho das páginas
- `footer.php` - Rodapé das páginas
- `modalTrocarProduto.php` - Modal para troca de produtos
- `modalSelecionarProduto.php` - Modal para seleção de produtos
- `modalAlterarProduto.php` - Modal para alteração de produtos
- `produtoCadastrado.php` - Template de produto cadastrado
- `cadastrarProdutos.php` - Formulário de cadastro de produtos

#### `/css`
- Arquivos de estilização

#### `/img`
- Imagens e recursos visuais

#### `/uploads`
- Arquivos enviados pelos usuários

#### `/scripts`
- Scripts JavaScript

### Arquivos Principais
- `index.php` - Página inicial
- `dashboard.php` - Painel de controle
- `produtos.php` - Listagem de produtos
- `todos_os_itens.php` - Visualização de todos os itens
- `trocas.php` - Página de trocas
- `trocas_populares.php` - Trocas mais populares
- `adicionados_recentemente.php` - Itens recentemente adicionados
- `perfil.php` - Perfil do usuário
- `sobre.php` - Informações sobre o projeto

## Tecnologias Utilizadas
- PHP
- MySQL
- HTML5
- CSS3
- JavaScript

## Configuração
1. Clone o repositório
2. Configure o arquivo `backend/db.php` com suas credenciais de banco de dados
3. Execute o arquivo SQL para criar o banco de dados
4. Acesse o projeto através do servidor web

## Funcionalidades
- Cadastro e autenticação de usuários
- Cadastro de produtos
- Sistema de trocas
- Perfil de usuário
- Dashboard com estatísticas
- Recuperação de senha
- Upload de imagens 