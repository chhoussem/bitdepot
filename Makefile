install:
	composer install
	php ./app/check.php
	bower install
	cd node/ && npm install && cd ../

tests:
	./bin/phing -f app/build.xml
	./bin/phpunit -c app/