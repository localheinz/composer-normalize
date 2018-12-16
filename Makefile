.PHONY: coverage cs infection it stan test

it: cs stan test

coverage: vendor
	vendor/bin/phpunit --configuration=test/Integration/phpunit.xml --coverage-text

cs: vendor
	mkdir -p .php-cs-fixer
	vendor/bin/php-cs-fixer fix --config=.php_cs --diff --verbose

infection: vendor
	mkdir -p .infection
	vendor/bin/infection --ignore-msi-with-no-mutations --min-covered-msi=100 --min-msi=70

stan: vendor
	mkdir -p .phpstan
	vendor/bin/phpstan analyse --configuration=phpstan.neon src test

test: vendor
	vendor/bin/phpunit --configuration=test/Integration/phpunit.xml

vendor: composer.json composer.lock
	composer validate
	composer install
