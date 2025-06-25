# EcoEscambo

## Descrição
EcoEscambo é uma plataforma web para troca de produtos, promovendo uma troca sem uso de dinheiro. A plataforma permite que usuários cadastrem seus produtos e realizem trocas com outros usuários de forma segura e organizada.

## Estrutura do Projeto

### Diretórios Principais

#### `/app`
- `/controllers` - Controladores da aplicação
  - `Controller.php` - Controlador base
  - `dbController.php` - Gerenciamento do banco de dados
  - `MessagesController.php` - Gerenciamento de mensagens
  - `ProductsController.php` - Gerenciamento de produtos
  - `userController.php` - Gerenciamento de usuários
- `/models` - Modelos e componentes
  - `modalSelecionarProduto.php` - Modal para seleção de produtos
  - `modalTrocarProduto.php` - Modal para troca de produtos
  - `navbarRouter.php` - Roteamento da barra de navegação
- `/views` - Views da aplicação
  - `dashboard.php` - Painel do usuário
  - `master.php` - Template principal
  - `produtos.php` - Listagem de produtos
  - `trocas.php` - Página de trocas
- `/static`
  - `/js` - Scripts
  - `/css` - Arquivos de estilo
  - `/uploads` - Arquivos enviados pelos usuários
- `/router` - Sistema de roteamento

## Tecnologias Utilizadas
- PHP 7.4+
- MySQL
- HTML5
- CSS3
- JavaScript
- Bootstrap 5
- PDO para conexão com banco de dados

## Funcionalidades Implementadas
- ✅ Cadastro e autenticação de usuários
- ✅ Cadastro, edição e exclusão de produtos
- ✅ Sistema de trocas entre usuários
- ✅ Upload de imagens de produtos
- ✅ Dashboard com produtos do usuário
- ✅ Sistema de notificações via modais
- ✅ Validação de dados e segurança
- ✅ Interface responsiva com Bootstrap
- ✅ Filtros com nome e categoria na página de produtos
- ✅ Filtros com nome e categoria no inventário
- ✅ Implementação de AJAX para filtros
- ✅ Paginação de produtos (carregar mais 10 produtos)
- ✅ Implementação de AJAX para o carregar mais
- ✅ Melhoria no código com PHPStan

## Funcionalidades em Desenvolvimento
- 🔄 Chat entre usuários que realizaram troca
- 🔄 Testes Unitários com PHPUnit
- 🔄 Página do usuario
- 🔄 E-mail de recuperação de Senha

## Configuração
1. Clone o repositório
2. Configure o banco de dados MySQL
3. Importe o arquivo SQL para criar as tabelas necessárias
4. Configure as credenciais do banco em `app/controllers/dbController.php`
5. Acesse o projeto através do servidor web

## Estrutura do Banco de Dados
- `users` - Tabela de usuários
  - `id` - Identificador único
  - `nome` - Nome do usuário
  - `email` - Email do usuário
  - `senha` - Senha criptografada
- `categorias` - Tabela de categorias de produtos
  - `id` - Identificador único
  - `nome` - Nome da categoria (único)
- `produtos` - Tabela de produtos
  - `id` - Identificador único
  - `nome` - Nome do produto
  - `descricao` - Descrição do produto
  - `fk_categoria` - Categoria do produto (referência à tabela categorias)
  - `img` - Caminho da imagem
  - `idUser` - Usuário dono do produto (referência à tabela users)
- `troca` - Tabela de trocas
  - `id` - Identificador único
  - `idUserDesejado` - Usuário que deseja o produto
  - `idUser` - Usuário que oferece o produto
  - `idProdDesejado` - Produto desejado
  - `idProdUser` - Produto oferecido
  - `Status` - Status da troca (0: pendente, 1: confirmada, -1: rejeitada)

## Contribuição
Para contribuir com o projeto:
1. Faça um fork do repositório
2. Crie uma branch para sua feature (`git checkout -b feature/nova-feature`)
3. Commit suas mudanças (`git commit -m 'Adiciona nova feature'`)
4. Push para a branch (`git push origin feature/nova-feature`)
5. Abra um Pull Request 