# Symfony Web Service

This repo build base structures for continue developing API:

# Requirements!

  - Write log every requests
  - Authenticate - Permission on user resources
  - Handle errors and exception nicely
  - API key inserted in header named "Authorization" (Please checkout postman collection export).



### Installation

Mysql import

```sh
$ mysql -u username -p database_name < install/symfony.sql
```

Symfony

```sh
$ cd projects/
$ git clone ...
$ cd my_project_name/
$ composer install
```

```sh
<VirtualHost *:80>
    ServerName symfony.tv
    ServerAlias www.symfony.tv
    DocumentRoot /path_to/web
    <Directory /path_to/web>
        Options FollowSymLinks
        AllowOverride All
        Order Allow,Deny
        Allow from All
    </Directory>
</VirtualHost>
```

Config parameters /app/config/parameters.yml

### Development

Symfony:

 - BaseController: Implement Common Method
 - WsListener: Handle Write Log Database and Authenticate (almost like middleware Slim Framework)
 - ExceptionListener: Handle Exception and Errors (Write Log and Response)

Database:

 - Log: store [uid] because need for reports and api key belong to user may change sometime
 - User: api key belong to user so it's should be unique

Testing:

 - Import postman collection: /install/Symfony.postman_collection

### Todos

 - Create setting file for common error/exception messages
 - Authenticate should not get MySQL for every request. Consider implement Redis or Json Web Token (JWT)
 - Implement Authorization
 - Implement some common method in **BaseController**. It's up to use which of Frontend Framework sample (KendoUI, Angularjs...)

License
----

MIT


**Free Software, Hell Yeah!**