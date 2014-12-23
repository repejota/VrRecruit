migrate: clean-migrate
	php vendor/ruckusing/ruckusing-migrations/ruckus.php db:migrate ENV=development
	php vendor/ruckusing/ruckusing-migrations/ruckus.php db:migrate ENV=test

clean-migrate:
	mysql -u ubuntu vreasy_task_confirmation -e "drop table if exists messages, schema_migrations, tasks;"
	mysql -u ubuntu vreasy_task_confirmation_test -e "drop table if exists messages, schema_migrations, tasks;"

test: test-clean test-php test-angular

test-clean:
	php vendor/codeception/codeception/codecept clean

test-php: test-build-php test-acceptance-php
	 php vendor/codeception/codeception/codecept run --debug

test-build-php:
	 php vendor/codeception/codeception/codecept build

test-acceptance-php:
	php vendor/codeception/codeception/codecept run acceptance --debug

test-unit-php:
	php vendor/codeception/codeception/codecept run unit --debug

test-functional-php:
	php vendor/codeception/codeception/codecept run functional --debug

test-angular:
	 karma start karma.conf.js --log-level debug --single-run

phantom:
	phantomjs --webdriver=4444

