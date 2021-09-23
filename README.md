# Orbiter\Satellite

[![Latest Stable Version](https://poser.pugx.org/orbiter/satellite/version.svg)](https://packagist.org/packages/orbiter/satellite)
[![Latest Unstable Version](https://poser.pugx.org/orbiter/satellite/v/unstable.svg)](https://packagist.org/packages/orbiter/satellite)
[![Total Downloads](https://poser.pugx.org/orbiter/satellite/downloads.svg)](https://packagist.org/packages/orbiter/satellite)
[![Github actions Build](https://github.com/bemit/satellite/actions/workflows/blank.yml/badge.svg)](https://github.com/bemit/satellite/actions)
[![PHP Version Require](http://poser.pugx.org/orbiter/satellite/require/php)](https://packagist.org/packages/orbiter/satellite)

- `orbiter/satellite`
- event handler and default `SatelliteApp` event
- implements **PSR-14** Event Dispatcher and Listener
- using [`InvokerInterface`](https://github.com/PHP-DI/Invoker/blob/2.0.0/src/InvokerInterface.php) to execute anything, **PSR-11** compatible

See [satellite-app](https://github.com/bemit/satellite-app) for a ready to use template or install:

```shell
composer require orbiter/satellite
```

## Dev Notices

Commands to setup and run e.g. tests:

```bash
# on windows:
docker run -it --rm -v %cd%:/app composer install

docker run -it --rm -v %cd%:/var/www/html php:8.0-cli-alpine sh

docker run --rm -v %cd%:/var/www/html php:8.0-cli-alpine sh -c "cd /var/www/html && ./vendor/bin/phpunit --testdox -c phpunit-ci.xml --bootstrap vendor/autoload.php"

# on unix:
docker run -it --rm -v `pwd`:/app composer install

docker run -it --rm -v `pwd`:/var/www/html php:8.0-cli-alpine sh

docker run --rm -v `pwd`:/var/www/html php:8.0-cli-alpine sh -c "cd /var/www/html && ./vendor/bin/phpunit --testdox -c phpunit-ci.xml --bootstrap vendor/autoload.php"
```

## License

This project is free software distributed under the [**MIT LICENSE**](LICENSE).

### Contributors

By committing your code to the code repository you agree to release the code under the MIT License attached to the repository.

***

Maintained by [Michael Becker](https://mlbr.xyz)
