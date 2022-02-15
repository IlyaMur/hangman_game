install:
	composer install

start:
	./bin/hangman

test:
	composer exec phpunit tests

lint:
	composer exec phpcs -- --standard=PSR12 src tests

test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml

test-html:
	composer exec --verbose phpunit tests --  --coverage-html build/
