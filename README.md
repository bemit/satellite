# Orbiter\Satellite

[![Latest Stable Version](https://poser.pugx.org/orbiter/satellite/version.svg)](https://packagist.org/packages/orbiter/satellite)
[![Latest Unstable Version](https://poser.pugx.org/orbiter/satellite/v/unstable.svg)](https://packagist.org/packages/orbiter/satellite)
[![Total Downloads](https://poser.pugx.org/orbiter/satellite/downloads.svg)](https://packagist.org/packages/orbiter/satellite)
[![Github actions Build](https://github.com/bemit/satellite/actions/workflows/blank.yml/badge.svg)](https://github.com/bemit/satellite/actions)
[![PHP Version Require](http://poser.pugx.org/orbiter/satellite/require/php)](https://packagist.org/packages/orbiter/satellite)

- **PSR-14** Event Dispatcher and Listener
- using [`InvokerInterface`](https://github.com/PHP-DI/Invoker/blob/2.0.0/src/InvokerInterface.php) to execute anything, **PSR-11** compatible
- includes the optional `SatelliteApp` event, used by [satellite-app](https://github.com/bemit/satellite-app)

Check [satellite-app](https://github.com/bemit/satellite-app) for a ready to use template, or install just this event library:

```shell
composer require orbiter/satellite
```

Dependencies, using [`PHP-DI`](https://php-di.org/) here, but possible with any PSR-11 container and any implementation of [`InvokerInterface`](https://github.com/PHP-DI/Invoker):

```injectablephp
use function DI\autowire;
use function DI\get;

$dependencies = [
    Satellite\Event\EventListenerInterface::class => autowire(Satellite\Event\EventListener::class),
    Psr\EventDispatcher\ListenerProviderInterface::class => get(Satellite\Event\EventListenerInterface::class),
    Satellite\Event\EventDispatcher::class => autowire()
        ->constructorParameter('listener', get(Psr\EventDispatcher\ListenerProviderInterface::class))
        ->constructorParameter('invoker', get(Invoker\InvokerInterface::class)),
    Psr\EventDispatcher\EventDispatcherInterface::class => get(Satellite\Event\EventDispatcher::class),
];
```

For full invocation PSR-11 injection based on `Reflection`, set up the `Invoker` with the included `InvokerTypeHintContainerResolver`:

```injectablephp
/**
 * @var $invoker \Invoker\Invoker
 */
$invoker = $container->get(\Invoker\Invoker::class);
$invoker->getParameterResolver()->prependResolver(
    new Satellite\InvokerTypeHintContainerResolver($container)
);
```

## Dev Notices

Commands to set up and run e.g. tests:

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

## Versions

This project adheres to [semver](https://semver.org/), **until `1.0.0`** and beginning with `0.1.0`: all `0.x.0` releases are like MAJOR releases and all `0.0.x` like MINOR or PATCH, modules below `0.1.0` should be considered experimental.

## License

This project is free software distributed under the [**MIT LICENSE**](LICENSE).

### Contributors

By committing your code to the code repository you agree to release the code under the MIT License attached to the repository.

***

Maintained by [Michael Becker](https://mlbr.xyz)
