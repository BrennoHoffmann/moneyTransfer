
# Money Transfer

Money transfer nada mais é do que um simples projeto que simula transações monetarias.

# Tecnologias

As tecnologias a seguir foram usadas no desenvolvimento:

- [PHP](https://www.php.net/)
- [Laravel](https://laravel.com/)
- [MySQL](https://www.mysql.com/)
- [Laravel Sanctum](https://laravel.com/docs/8.x/sanctum)

# Como rodar

Após clonar o repositório, siga o passo a passo a seguir:

- Crie um banco de dados MySQL;
- Edite o arquivo .env com as informações do seu banco;
- No diretório do projeto `composer install && npm install`;
- Rode as migrations com `php artisan migrate`;
- Popule o banco de dados com `php artisan db:seed`;
- E por fim, utilize `php artisan serve` para rodar o projeto.

  

# Fazendo login na API

  

Para conseguir fazer requisições, será necessário fazer login na API com um dos usuários que foi criado pela factorie, para isso, basta utilizar o seguinte payload:

  
  

### POST em /api/login

```
{
	"email": "new@user.com",
	"password": "password"
}
```

O resultado deve ser algo semelhante ao seguinte payload:

```
{
    "user": {
        "id": 1,
        "name": "New User",
        "email": "new@user.com",
        "email_verified_at": null,
        "doc": "123456789123",
        "user_type": 0,
        "created_at": null,
        "updated_at": null
    },
    "token": "1|1GcKpdrikjJ6pmEgPME7aBtvMU5EibLBtLBMGWAq"
}
```

Esse token é gerado a cada requisição de login e deve ser acrescentado no no Header de todas as seguintes requisições.

# Transferência do usuário para o lojista

Usuários do tipo lojista estão salvos no banco como *user_type = 1* e  **não** podem realizar transferências, apenas receber. Para realizar uma transação do usuário para o lojista, ou entre usuários, basta utilizar o seguinte payload:

### POST em /api/update
```
{
	"value": 100.00,
	"payee": 4,
	"payer": 3
}
```
> *Obs.: Não se esqueça de utilizar o token gerado no login!*