# Article CRUD application

## Installation
git clone https://github.com/dwasil/articles_crud.git

cd articles_crud

composer install

create .env from .env.dist

## Run local service:
symfony server:start

## REST API Documentation (OpenAPI)
https://app.swaggerhub.com/apis/wasil/Article_CRUD/1.0.0#/Article
http://127.0.0.1:8000/api/doc
rest-api-doc.zip

## Run tests
create .env.test
php ./vendor/bin/phpunit