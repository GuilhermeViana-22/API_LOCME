# Personalização do Swagger UI com L5-Swagger

  

## 1. Publicar os assets do Swagger UI (se ainda não tiver feito):

  

```bash

php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider" --tag=assets

```

# 2. Criar um arquivo de customização CSS

Crie um arquivo em:  
`public/vendor/l5-swagger/css/custom.css` com o seguinte conteúdo:


```css

/* Tema Verde e Preto */
.swagger-ui {
  --green-dark: #0a290a;
  --green-medium: #145214;
  --green-light: #1f7a1f;
  --black: #121212;
  --white: #f0f0f0;
}

/* Fundo geral */
.swagger-ui, .opblock {
  background-color: var(--black) !important;
  color: var(--white) !important;
}

/* Barra superior */
.swagger-ui .topbar {
  background-color: var(--green-dark) !important;
}

/* Títulos */
.swagger-ui .info h2, .swagger-ui .opblock .opblock-summary-path {
  color: var(--green-light) !important;
}

/* Botões */
.swagger-ui .btn {
  background-color: var(--green-medium) !important;
  border-color: var(--green-light) !important;
}

/* Painéis */
.swagger-ui .opblock {
  border-color: var(--green-medium) !important;
  background-color: var(--black) !important;
}

/* Links */
.swagger-ui a {
  color: var(--green-light) !important;
}


```
### 3. Configurar no arquivo `config/l5-swagger.php`

Adicione a linha abaixo:

```bash
'additional_css' => [
    'vendor/l5-swagger/css/custom.css', // Adicione esta linha
],

```
### 4. Limpar caches e regenerar

```bash

php artisan cache:clear
php artisan view:clear
php artisan l5-swagger:generate

```

### Opções adicionais

```bash
.swagger-ui {
  --neon-green: #0f0;
  --black: #000;
}

.swagger-ui, .opblock {
  background-color: var(--black) !important;
  color: var(--neon-green) !important;
  filter: brightness(0.9);
}

```