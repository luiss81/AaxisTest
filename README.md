# Description

This solution runs on Docker with the following image specifications:

- PHP 8.2.20-fpm
- Nginx 1.18
- PostgreSQL 15.1

Tested on Linux (Ubuntu 22.04):

1. Docker version 26.1.3, build b72abbb
2. docker-compose version 1.23.2, build 1110ad01

# Usage

1. Clone repository, `cd` inside.
1. Create `.env` file in `/` directory according to your needs, or just copy template `.env.dist`
1. Review `docker-compose.yml` and change according to the comments inside. 
1. Run:

<pre>
docker-compose build
docker-compose up
</pre>

5. Log into container with
<pre>
docker exec -it test_php82 bash
</pre>

6. Run composer
<pre>
composer install
</pre>

7. Run migrations
<pre>
php bin/console doctrine:migrations:migrate
</pre>

From this point forward, application should be available under `http://localhost:8088/`, where port `8088` is defined in `docker-compose.yml`.

### Api testing

To test this API, you can install Postman or use another Rest API client you like. Below I am going to describe the steps to use the Postman API client

#### Steps
1. Import the collection tools/[test_postman_collection.json](..%2Ftest_postman_collection.json)
2. Open the REGISTER USER tab and put data required in the body section to create an user
3. In the LOGIN CHECK section, set the user and password created to obtain the the api token
4. In Edit->Variables section, put the content in the token variable
5. Set Products in json format on CREATE PRODUCTS section to load products
6. Set Products in json format on UPDATE PRODUCTS section to modify products

# Links
- https://www.postman.com/api-platform/api-client/
- https://symfony.com/
- https://www.docker.com/
- https://docs.docker.com/compose/
- https://www.php.net/
- https://www.nginx.com/
- https://www.postgresql.org/
- https://github.com/squizlabs/PHP_CodeSniffer