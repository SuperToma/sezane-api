# Sezane technical test

## Stack management

### Launch project
`docker-compose up`

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