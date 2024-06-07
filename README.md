# Sezane technical test

## Stack management

### Launch project
`docker-compose up`

### Load fixtures
`docker exec -it sezane-api sh -c "php bin/console doctrine:fixtures:load --no-interaction"`

### URLs

#### List all managers
```
curl http://localhost:8888/managers
curl http://localhost:8888/managers?limit=2&page=2
```

#### List all shops
```
curl http://localhost:8888/shops
curl http://localhost:8888/shops?limit=2&page=2
```

#### Get a shop
```
curl http://localhost:8888/shop/83
```
#### Create a new shop
```
curl -X POST \
-d 'managerId=144' -d 'name=new+shop' \
-d 'address=new+address' -d 'latitude=1.2345' \
-d 'longitude=73.67' http://localhost:8888/shop
```

#### List shops
`http://localhost:8888/shops`

With distance from current position
`http://localhost:8888/shops?latitude=0.12&longitude=3.45`

With distance from current position
`http://localhost:8888/shops?latitude=0.12&longitude=3.45`

With distance from current position and a limit meters
`http://localhost:8888/shops?latitude=0.12&longitude=3.45&limitMeters=6000000`

#### List products with quantity per shop
`http://localhost:8888/products`

#### Get a product
`http://localhost:8888/product/1101`

#### List manager's available products with the stock per shop
`http://localhost:8888/products?managerId=146`

### Launch shell
`docker exec -it sezane-api bash`

### Tooling commands
#### CS Fixer with docker:
`docker exec -it sezane-api sh -c "vendor/bin/php-cs-fixer fix --config .php-cs-fixer.dist.php"`

#### CS Fixer locally:
`api/vendor/bin/php-cs-fixer fix --config api/.php-cs-fixer.dist.php`

#### PHPStan
`docker exec -it sezane-api sh -c "vendor/bin/phpstan"`

#### PHPStan locally:
`api/vendor/bin/phpstan --configuration=api/phpstan.dist.neon`