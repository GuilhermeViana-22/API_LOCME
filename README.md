# 🏖️ API LOCME - Sistema de Gerenciamento Turístico

O **API LOCME** é uma plataforma robusta e completa desenvolvida para o gerenciamento inteligente de **agentes turísticos**, **empresas do setor** e **representantes comerciais** no ramo do turismo. Este sistema foi projetado para centralizar e otimizar a gestão de relacionamentos comerciais, facilitando a conexão entre diferentes atores do ecossistema turístico.

## 🎯 Sobre o Projeto

Este é um **projeto proprietário** desenvolvido especificamente para atender às necessidades do mercado turístico brasileiro, oferecendo uma solução completa para:

- **Gestão de Agentes de Viagem**: Cadastro, acompanhamento e gerenciamento de agentes e operadores turísticos
- **Administração de Empresas**: Controle completo de empresas parceiras, fornecedores e prestadores de serviços turísticos
- **Gerenciamento de Representantes**: Sistema dedicado para representantes comerciais e seus territórios de atuação
- **Relacionamento Comercial**: Ferramentas para fortalecer parcerias e otimizar a comunicação entre stakeholders

## ✨ Principais Funcionalidades

### 🔐 Sistema de Autenticação e Autorização
- Autenticação segura com JWT (Laravel Passport)
- Gerenciamento de perfis hierárquicos (Admin, Gerente, Agente, Representante)
- Controle de acesso baseado em funções e permissões
- Sistema de tokens para integração com aplicações externas

### 👥 Gestão de Agentes Turísticos
- Cadastro completo de agentes e operadores
- Histórico de transações e comissões
- Acompanhamento de performance e vendas
- Sistema de avaliações e feedback

### 🏢 Administração de Empresas
- Registro detalhado de empresas parceiras
- Gestão de contratos e acordos comerciais
- Monitoramento de relacionamentos comerciais
- Relatórios de performance empresarial

### 🤝 Controle de Representantes
- Gerenciamento de representantes comerciais
- Definição de territórios e áreas de atuação
- Acompanhamento de metas e resultados
- Sistema de comissionamento

### 📊 Dashboard e Relatórios
- Painéis analíticos em tempo real
- Relatórios de vendas e performance
- Métricas de relacionamento comercial
- Exportação de dados em múltiplos formatos

## 🛠️ Tecnologias Utilizadas

- **Backend**: [Laravel](https://laravel.com/) - Framework PHP robusto e moderno
- **Autenticação**: [Laravel Passport](https://laravel.com/docs/passport) - Sistema JWT completo
- **Banco de Dados**: [MySQL](https://www.mysql.com/) - Banco relacional confiável
- **API**: RESTful API com documentação Swagger/OpenAPI
- **Cache**: Redis para otimização de performance
- **Queue**: Sistema de filas para processamento assíncrono

## 📋 Requisitos do Sistema

- **PHP** >= 8.1
- **Composer** >= 2.0
- **MySQL** >= 8.0 ou **PostgreSQL** >= 13
- **Redis** (recomendado para cache)
- **Node.js** >= 16 (para build de assets)

## 🚀 Instalação e Configuração

### 1. Clonagem do Repositório
```bash
git clone https://github.com/GuilhermeViana-22/API_LOCME.git
cd API_LOCME
```

### 2. Instalação de Dependências
```bash
# Instalar dependências PHP
composer install

# Instalar dependências Node.js
npm install
```

### 3. Configuração do Ambiente
```bash
# Copiar arquivo de configuração
cp .env.example .env

# Gerar chave da aplicação
php artisan key:generate
```

### 4. Configuração do Banco de Dados
Edite o arquivo `.env` com suas credenciais:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=api_locme
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 5. Configuração de Permissões
```bash
# Linux/Mac
chmod -R 775 storage bootstrap/cache

# Windows (PowerShell como Administrador)
icacls storage /grant Users:(OI)(CI)F /T
icacls bootstrap\cache /grant Users:(OI)(CI)F /T
```

### 6. Execução das Migrações
```bash
# Executar migrações e seeders
php artisan migrate --seed

# Instalar e configurar Passport
php artisan passport:install
```

### 7. Inicialização do Servidor
```bash
# Servidor de desenvolvimento
php artisan serve

# Build de assets (em outro terminal)
npm run dev
```

## 📚 Documentação da API

A documentação completa da API está disponível através do Swagger UI:

- **Local**: `http://localhost:8000/api/documentation`
- **Produção**: `https://seu-dominio.com/api/documentation`

### Principais Endpoints

#### 🔐 Autenticação
- `POST /api/register` - Registro de usuários
- `POST /api/login` - Login e obtenção de token
- `POST /api/logout` - Logout e revogação de token

#### 👥 Agentes
- `GET /api/agents` - Listar agentes
- `POST /api/agents` - Criar novo agente
- `GET /api/agents/{id}` - Detalhes do agente
- `PUT /api/agents/{id}` - Atualizar agente

#### 🏢 Empresas
- `GET /api/companies` - Listar empresas
- `POST /api/companies` - Criar nova empresa
- `GET /api/companies/{id}` - Detalhes da empresa

#### 🤝 Representantes
- `GET /api/representatives` - Listar representantes
- `POST /api/representatives` - Criar representante
- `GET /api/representatives/{id}/territory` - Território do representante

## 🔧 Configuração Avançada

### Cache Redis
```env
CACHE_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

### Filas de Processamento
```bash
# Executar worker de filas
php artisan queue:work

# Configurar supervisor para produção
sudo supervisorctl start laravel-worker:*
```

### Email SMTP
```env
MAIL_MAILER=smtp
MAIL_HOST=seu-smtp.com
MAIL_PORT=587
MAIL_USERNAME=seu-email
MAIL_PASSWORD=sua-senha
```

## 🧪 Testes

```bash
# Executar todos os testes
php artisan test

# Testes com coverage
php artisan test --coverage

# Testes específicos
php artisan test --filter AgentTest
```

## 📈 Performance e Otimização

### Cache de Configuração
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Otimização para Produção
```bash
composer install --optimize-autoloader --no-dev
php artisan optimize
npm run production
```

## 🔒 Segurança

- Autenticação JWT com refresh tokens
- Criptografia de dados sensíveis
- Validação rigorosa de entrada
- Rate limiting em endpoints críticos
- Logs de auditoria completos

## 📞 Suporte e Contato

Para suporte técnico ou dúvidas sobre o sistema:

- **Desenvolvedor**: Guilherme Viana
- **Email**: guilherme.viana@locme.com.br
- **GitHub**: [@GuilhermeViana-22](https://github.com/GuilhermeViana-22)

## 📄 Licença

Este é um **projeto proprietário**. Todos os direitos reservados. O uso, distribuição ou modificação deste código requer autorização expressa do proprietário.

---

**© 2024 LOCME - Todos os direitos reservados**

*Desenvolvido com ❤️ para revolucionar o mercado turístico brasileiro*

