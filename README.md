# HorusApp API

API está disponível no link [fiap-horusapp.herokuapp.com](fiap-horusapp.herokuapp.com)

## Setup

- **Banco de dados**
<br>
Banco de dados utilizado foi o Postgres e pode ser setado seguindo o arquivo `db.sql`

```
composer install
```

## Libs e Frameworks

- **Router**
<br>
[Slim](https://gist.github.com/pbroschwitz/3891293)

- **Encriptação**
<br>
[Mcrypt](http://php.net/manual/en/function.mcrypt-encrypt.php)

- **HTTP**
<br>
[Symfony psr-http-message-bridge](https://github.com/symfony/psr-http-message-bridge)

## Server
- Server estará disponível http://localhost/8080
```
composer run-script start
```

## Endpoints desenvolvidos

###Login
| Método | URL    | Retorno                      |
|--------|--------|------------------------------|
| POST   | /login | Token de usuário caso exista |

###Usuários

| Método | URL         | Retorno                                                         |
|--------|-------------|-----------------------------------------------------------------|
| GET    | /users/{id} | Usuário do `id` selecionado                                     |
| POST   | /users      | Cria novo usuário caso o email enviado ainda nao exista na base |
| PUT    | /users/{id} | Atualiza usuário do `id` selecionado                   |
| DELETE | /users/{id} | Deleta usuário do `id` selecionado                              |
<br>
Métodos `GET`, `PUT` e `DELETE` são validados com o token e email para garantir que o usuario esta logado


###Vídeos
| Método | URL          | Retorno                                          |
|--------|--------------|--------------------------------------------------|
| GET    | /videos      | Todos os vídeos                                  |
| GET    | /videos/{id} | Vídeo do `id` selecionado                        |
| POST   | /videos      | Cria novo vídeo relacionado com o usuário logado |
| PUT    | /videos/{id} | Atualiza vídeo do `id` selecionado               |
| DELETE | /videos/{id} | Deleta vídeo do `id` selecionado                 |
<br>
Todos os métodos são validados com o token e email para garantir que o usuario esta logado
