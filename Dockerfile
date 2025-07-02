# Dockerfile
FROM php:8.1-fpm-alpine

# Instalar dependências do sistema
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    mysql-client \
    supervisor \
    bash \
    vim

# Instalar extensões PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir diretório de trabalho
WORKDIR /var/www/html

# Copiar arquivos de dependências primeiro (para cache)
COPY composer.json composer.lock package.json package-lock.json ./

# Instalar dependências PHP
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Instalar dependências Node.js (incluindo dependências de desenvolvimento para build)
RUN npm ci

# Copiar código da aplicação
COPY . .

# Definir permissões
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Copiar arquivo de configuração do ambiente
COPY .env.example .env

# Gerar chave da aplicação
RUN php artisan key:generate

# Compilar assets
RUN npm run production

# Limpar dependências de desenvolvimento e cache do npm
RUN npm prune --production && npm cache clean --force

# Criar diretório para logs do supervisor
RUN mkdir -p /var/log/supervisor

# Configurar supervisor para gerenciar processos
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expor porta
EXPOSE 8000

# Comando de inicialização
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
