# lx-utils

## Notice

When running on a real project, it's a good idea to backup the project files first!
Just to be sure that what you are doing matches what you're expecting.

## Usage

php run/cleanUpSquareBrackets.php /path/you/want/to/clean/up

## Development set-up

* Clone project locally:
```
git clone https://github.com/eyroot/lx-utils lx-utils
cd lx-utils
```

* Set-up project and install composer deps:
```
composer install
```

* Run unit testing:
```
mkdir testing/tmp
cd testing/
../vendor/bin/phpunit
```

* Check the code coverage of tests by opening in browser:
```
file:///tmp/coverage-lx-utils/index.html
```
