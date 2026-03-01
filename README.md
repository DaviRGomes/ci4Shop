# CI4Shop 🛒

Sistema de pedidos online desenvolvido com **CodeIgniter 4**, **jQuery** e **Bootstrap 5**.

---

## 📋 Funcionalidades

- ✅ Login diferenciado para **Pessoa Física (PF)** e **Pessoa Jurídica (PJ)**
- ✅ Cadastro de novos usuários com campos específicos por tipo
- ✅ Catálogo de produtos com filtro por categoria e busca
- ✅ Carrinho de compras com controle de estoque em tempo real
- ✅ Finalização de pedido via AJAX
- ✅ Tela de **Resumo dos Pedidos** com detalhamento completo
- ✅ Cancelamento de pedido (com devolução automática ao estoque)
- ✅ Troca de senha pelo perfil do usuário
- ✅ Interface responsiva com Bootstrap 5

---

## 🗄️ Modelagem do Banco de Dados

```
users
  ├── id, type (pf/pj), name, email, password
  ├── cpf, birth_date              (Pessoa Física)
  ├── cnpj, company_name, trade_name  (Pessoa Jurídica)
  └── phone, active, created_at, updated_at

products
  └── id, name, description, price, stock, category, image_url, active

orders
  └── id, user_id (FK), status, total, notes, created_at, updated_at

order_items
  └── id, order_id (FK), product_id (FK), quantity, unit_price, subtotal
```

---

## ⚙️ Pré-requisitos

- PHP >= 7.4 (recomendado PHP 8.1+)
- MySQL 5.7+ ou MariaDB 10.3+
- Apache com `mod_rewrite` habilitado (ou Nginx equivalente)
- Composer

---

## 🚀 Passo a Passo para Rodar o Projeto

### 1. Clonar o repositório

```bash
git clone https://github.com/seu-usuario/ci4shop.git
cd ci4shop
```

### 2. Instalar dependências do CodeIgniter 4

```bash
composer install
```

> Se não tiver o CodeIgniter instalado, execute:
> ```bash
> composer require codeigniter4/framework
> ```

### 3. Configurar o arquivo de ambiente

Copie o arquivo de exemplo e edite:

```bash
copy env .env
```

Edite o `.env` com suas configurações de banco:

```env
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost/ci4shop/public/'

database.default.hostname = localhost
database.default.database = ci4shop
database.default.username = root
database.default.password = SUA_SENHA
database.default.DBDriver = MySQLi
```

### 4. Criar e popular o banco de dados
Via phpMyAdmin:
1. Crie um banco chamado `ci4shop`
2. Importe o arquivo `app/Database/Migrations/20260228142005_create_database.sql` (schema)
3. Importe o arquivo `app/Database/Seeds/20260228143408_seeds.sql` (dados iniciais)

### 5. Configurar permissões de escrita

```bash
chmod -R 777 writable/
```

### 6. Acessar o sistema

Abra o navegador e acesse:

```
http://localhost/ci4shop/public/
```

---

## 👤 Usuários de Teste

| Tipo | E-mail | Senha |
|------|--------|-------|
| Pessoa Física | davi@email.com | password |
| Pessoa Física | jaqueline@email.com | password |
| Pessoa Jurídica | empresa@email.com | password |
| Pessoa Jurídica | distribuidora@email.com | password |

> A senha no banco está como hash BCrypt de `password`. 
---

## 🧪 Fluxo de Teste Sugerido

1. **Login PF:** Acesse com `davi@email.com` / `password`
   - Note o banner verde de "Pessoa Física"
   - Adicione produtos ao carrinho
   - Finalize o pedido
   - Visualize o resumo em "Meus Pedidos"

2. **Login PJ:** Acesse com `empresa@email.com` / `password`
   - Note o banner azul de "Pessoa Jurídica"
   - Realize um pedido e cancele-o
   - Verifique que o estoque foi devolvido

3. **Cadastro:** Acesse `/register` e crie uma conta nova (PF ou PJ)

4. **Trocar Senha:** Logado, acesse `/profile/password` e altere a senha do usuário

---

## 📁 Estrutura de Arquivos

```
ci4shop/
├── app/
│   ├── Config/
│   │   ├── Routes.php        # Rotas do sistema
│   │   └── Filters.php       # Filtro de autenticação
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── HomeController.php
│   │   └── OrderController.php
│   ├── Filters/
│   │   └── AuthFilter.php    # Middleware de sessão
│   ├── Models/
│   │   ├── UserModel.php
│   │   ├── ProductModel.php
│   │   ├── OrderModel.php
│   │   └── OrderItemModel.php
│   ├── Database/
│   │   ├── Migrations/
│   │   │   └── 20260228142005_create_database.sql  # Schema do banco
│   │   └── Seeds/
│   │       └── 20260228143408_seeds.sql             # Dados iniciais
│   └── Views/
│       ├── layouts/main.php  # Layout base com navbar + carrinho
│       ├── auth/
│       │   ├── login.php
│       │   ├── register.php
│       │   └── change_password.php  # Troca de senha
│       ├── home/index.php    # Catálogo de produtos
│       └── orders/
│           ├── index.php     # Resumo dos pedidos
│           └── show.php      # Detalhe do pedido
├── public/
│   ├── index.php
│   ├── .htaccess
│   ├── css/app.css
│   └── js/cart.js            # Lógica do carrinho (jQuery)
└── .env                      # Configurações do ambiente
```

---

## 🛠️ Tecnologias Utilizadas

| Tecnologia | Versão | Uso |
|-----------|--------|-----|
| CodeIgniter 4 | ^4.x | Framework PHP (MVC) |
| Bootstrap 5 | 5.3 | UI / Responsividade |
| jQuery | 3.7.1 | AJAX + DOM |
| Bootstrap Icons | 1.10 | Ícones |
| MySQL/MariaDB | 5.7+ | Banco de dados |

---

## 📝 Observações

- O carrinho é gerenciado via `localStorage` no frontend e enviado via AJAX ao finalizar
- Baixa de estoque ocorre transacionalmente junto com a criação do pedido
- Cancelamento de pedido devolve o estoque automaticamente
- O sistema diferencia visualmente PF (verde) e PJ (azul) após o login
