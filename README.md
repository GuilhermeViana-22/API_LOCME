# TEST3 Sistema de Gerenciamento de Usuários

Este projeto é uma API de gerenciamento de usuários desenvolvida com Laravel. Ele inclui autenticação com JWT (usando Laravel Passport), gerenciamento de permissões e funções de usuários (administrador e usuário comum).

## Funcionalidades

- Registro de usuários
- Login e logout com JWT
- Gerenciamento de perfis de usuário
- Atribuição de funções e permissões aos usuários
- Controle de acesso baseado em funções e permissões

## Tecnologias Utilizadas

- [Laravel](https://laravel.com/) - Framework PHP
- [Laravel Passport](https://laravel.com/docs/8.x/passport) - Autenticação com JWT
- [MySQL](https://www.mysql.com/) - Banco de dados relacional

## Requisitos

- PHP >= 7.3
- Composer
- MySQL

## Instalação

1. Clone o repositório:
    ```sh
    git clone https://github.com/seu-usuario/seu-repositorio.git
    ```

2. Navegue até o diretório do projeto:
    ```sh
    cd seu-repositorio
    ```

3. Instale as dependências do Composer:
    ```sh
    composer install
    ```

4. Crie um arquivo `.env` a partir do exemplo e configure suas credenciais de banco de dados:
    ```sh
    cp .env.example .env
    ```

5. Certifique-se de que as permissões estão corretas para os diretórios storage e bootstrap/cache. Eles devem ser graváveis pelo servidor web:
    ```sh
   chmod -R 775 storage
    
   chmod -R 775 bootstrap/cache
    ```
6. Gere a chave da aplicação:
    ```sh
    php artisan key:generate
    ```

7. Configure o banco de dados no arquivo `.env`:
    ```plaintext
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nome_do_banco
    DB_USERNAME=seu_usuario
    DB_PASSWORD=sua_senha
    ```

8. Execute as migrações e seeders:
    ```sh
    php artisan migrate --seed
    ```

9. Instale o Laravel Passport:
    ```sh
    php artisan passport:install
    ```

## Uso

### Registro de Usuário

Endpoint: `POST /api/register`

Corpo da Requisição:
```json
{
    "name": "Seu Nome",
    "email": "seuemail@example.com",
    "password": "suaSenha",
    "password_confirmation": "suaSenha"
}
```

# Documentação de Configuração do Laravel Passport

Antes de seu aplicativo poder emitir tokens de acesso pessoal, você precisará criar um cliente de acesso pessoal. 

## Passo 1: Instalar o Passport

Se você ainda não executou o comando `passport:install`, você deve executá-lo primeiro. Este comando criará as chaves de criptografia e os clientes necessários para o Laravel Passport.

```bash
php artisan passport:install
```

*Passo 2: Criar um Cliente de Acesso Pessoal*

Depois de executar passport:install, você pode criar um cliente de acesso pessoal. Se você já executou o comando passport:install, não é necessário executar este comando novamente.

Para criar um cliente de acesso pessoal, use o comando:
```bash
php artisan passport:client --personal
```

