# Article CRUD application

## Assignment: 

Headless (no UI) CMS for managing articles (CRUD).

## Description

Each article has its ID, title, body and timestamps (on creation and update)

Typical set of CRUD calls including reading one and all the articles. 

Creating/updating/deleting data is possible only if a secret token is provided in header HTTP_X-AUTH-TOKEN.   

For reading all the articles, the endpoint allow specifying a field to sort by including whether in an ascending or descending order + basic limit/offset pagination.

The whole client-server communication in a JSON format. 

The architecture is following Domain-Driven Design

The architecture is ready to communicate in different formats like XML (not implementated yet). 

## Installation

git clone https://github.com/dwasil/articles_crud.git

cd articles_crud

composer install

create .env from .env.dist

## Run local service

symfony server:start


## REST API Documentation (OpenAPI)

rest-api-doc.zip

https://app.swaggerhub.com/apis/wasil/Article_CRUD/1.0.0#/Article

http://127.0.0.1:8000/api/doc


## Run tests

create .env.test

php ./vendor/bin/phpunit
