bootstrap:
	composer install
	php artisan key:generate || true

stan:
	vendor/bin/phpstan

typecheck:
	vendor/bin/deptrac analyze

test:
	vendor/bin/pest --ci || true

fix:
	vendor/bin/pint || true
