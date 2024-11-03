# Projeto de API Rest para Cadastro de Fornecedores

Este projeto é uma API Restful desenvolvida em Laravel 10, que permite o cadastro, consulta, atualização e exclusão de fornecedores, permitindo a busca por CNPJ ou CPF. A API também integra com a [BrasilAPI](https://brasilapi.com.br/) para busca de informações de CNPJ.

## Tecnologias Utilizadas

-   PHP 8.1 ou superior
-   Laravel 10.x
-   MySQL 8.0 ou PostgreSQL
-   Composer
-   Docker (opcional)
-   Redis (opcional, para cache)

## Requisitos

-   PHP 8.1 ou superior com as extensões:
    -   OpenSSL
    -   PDO
    -   Mbstring
    -   Tokenizer
    -   XML
    -   cURL
-   Composer
-   Banco de Dados: MySQL ou PostgreSQL
-   Node.js e NPM (opcional, se for utilizar recursos de frontend)
-   Docker e Docker Compose (opcional, para ambiente dockerizado)

## Instalação

### Clonando o Repositório

Faça o clone do repositório para a sua máquina local:

```bash
git clone https://github.com/marciodeveloper/teste-dev-php.git
```

### Navegue até o diretório do projeto:

```bash
cd teste-dev-php
```

## Instalando as Dependências do Composer

```bash
composer install
```

## Configuração

### Arquivo `.env`

Copie o arquivo `.env.example` para `.env`:

```bash
cp .env.example .env
```

Gere a chave da aplicação:

```bash
php artisan key:generate
```

## Configurando o Banco de Dados

Edite o arquivo `.env` e configure as informações do seu banco de dados:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=projeto_fornecedores
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

Crie o banco de dados com o nome especificado (projeto_fornecedores).

## Execução

### Executando as Migrações

```bash
php artisan migrate
```

### Iniciando o Servidor de Desenvolvimento

```bash
php artisan serve
```

A aplicação estará disponível em `http://localhost:8000`.

## Rotas da API

## Endpoints Disponíveis

### Fornecedores

-   GET `/api/fornecedores` - Lista paginada de fornecedores

    -   Parâmetros de Query (opcionais):
        -   `nome`: Filtra fornecedores pelo nome.
        -   `tipo_documento`: Filtra por 'CPF' ou 'CNPJ'.
        -   `ordenarPor`: Campo para ordenação.
        -   `ordem`: Direção da ordenação ('asc' ou 'desc').
        -   `page`: Número da página.

-   GET /api/fornecedores/{id} - Detalhes de um fornecedor específico
-   POST /api/fornecedores - Cria um novo fornecedor
    -   Campos obrigatórios no corpo da requisição:
        -   `nome`: string
        -   `documento`: string (CPF ou CNPJ válido)
        -   `tipo_documento`: 'CPF' ou 'CNPJ'
    -   Campos opcionais:
        -   `email`: string (email válido)
        -   `telefone`: string
        -   `endereco`: string
-   PUT `/api/fornecedores/{id}` - Atualiza um fornecedor existente
    -   Mesmos campos do POST
-   DELETE `/api/fornecedores/{id}` - Exclui um fornecedor

### Busca por Documento

-   GET `/api/fornecedores/busca/{documento}` - Busca um fornecedor pelo CPF ou CNPJ
    -   Se o fornecedor estiver cadastrado, retorna seus dados.
    -   Se for um CNPJ válido e não estiver cadastrado, busca as informações na BrasilAPI.
    -   Se for um CPF, retorna mensagem informando que a consulta de CPF não é suportada.
    -   Se o documento for inválido, retorna mensagem de erro.

## Exemplo de Requisição

### Criar um Fornecedor

```bash
POST /api/fornecedores
Content-Type: application/json

{
  "nome": "Empresa ABC",
  "documento": "12345678000199",
  "tipo_documento": "CNPJ",
  "email": "contato@empresaabc.com",
  "telefone": "(11) 98765-4321",
  "endereco": "Rua das Flores, 100"
}
```

### Resposta de Sucesso (201 Created)

```json
{
    "id": 1,
    "nome": "Empresa ABC",
    "documento": "12345678000199",
    "tipo_documento": "CNPJ",
    "email": "contato@empresaabc.com",
    "telefone": "(11) 98765-4321",
    "endereco": "Rua das Flores, 100",
    "created_at": "2023-10-01T12:34:56.000000Z",
    "updated_at": "2023-10-01T12:34:56.000000Z"
}
```

## Testes Automatizados

### Executando os Testes

Os testes automatizados foram implementados para garantir o funcionamento correto das funcionalidades da API.

Execute os testes com o seguinte comando:

```bash
php artisan test
```

Os testes incluem:

-   Testes de Unidade: Verificação das validações e lógica de negócio.
-   Testes de Feature: Testes das rotas da API e respostas esperadas.

## Dockerização do Ambiente

### Pré-requisitos

-   Docker
-   Docker Compose

### Configuração

Edite o arquivo `.env` e atualize as seguintes variáveis:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=projeto_fornecedores
DB_USERNAME=root
DB_PASSWORD=senha
```

### Iniciando os Contêineres

Execute o comando:

```bash
docker-compose up -d
```

Os serviços configurados são:

-   app: Aplicação Laravel rodando em PHP 8.2
-   db: Banco de dados MySQL 8.0
-   redis: Servidor Redis para cache (opcional)

## Executando as Migrações no Contêiner

```bash
docker-compose exec app php artisan migrate
```

## Acessando a Aplicação

A aplicação estará disponível em `http://localhost:8000`.

## Cache

O cache foi implementado utilizando o driver configurado em CACHE_DRIVER no arquivo .env. Por padrão, está configurado para usar o file.

Para utilizar o Redis, atualize as configurações:

```env
CACHE_DRIVER=redis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

## Considerações Finais

-   Validação de Documentos: Foi implementado um helper para validar CPF e CNPJ, garantindo a integridade dos dados.
-   Repository Pattern: Utilizado para desacoplar a lógica de acesso a dados dos controladores, facilitando a manutenção e testes.
-   Padrões de Desenvolvimento: Seguem as boas práticas recomendadas pela comunidade Laravel, incluindo o uso de migrations, rotas organizadas e tratamento adequado de erros.
-   Segurança: As entradas do usuário são validadas cuidadosamente para evitar vulnerabilidades.
-   Documentação: Este README fornece todas as informações necessárias para instalação, configuração e uso da aplicação.

## Contato

**E-mail:** <jose.barthem@gmail.com>

**Celular:** (41) 98473-7903

Estou à disposição para quaisquer dúvidas ou esclarecimentos.
