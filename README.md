### API RESTFUL AGENDA (projeto teste)

Para utilizar você precisa:

-   renomear o arquivo `.env.example` para `.env` e atualizar suas informações;
-   levantar o serviço em Docker: `docker-compose up -d`;
-   acessar o container Docker: `docker-compose exec app bash`;
-   dentro do container rodar um `composer install`;
-   dentro do container, instalar o banco de dados:
    `php artisan migrate:fresh --seed`;

## Principais Comandos

Para levantar o serviço em Docker: `docker-compose up -d`
Para acessar o container: `docker-compose exec app bash`
Para atualizar a documentação Swagger: `php artisan l5-swagger:generate`

## Testes

Acesse o container Docker `docker-compose exec app bash` e execute o comando
`php artisan test`

## Documentação

Para acessar a documentação: http://localhost:8181/api/documentation#/
