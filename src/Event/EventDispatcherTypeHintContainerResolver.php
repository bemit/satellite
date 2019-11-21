<?php

namespace Satellite\Event;

use Invoker\ParameterResolver\ParameterResolver;
use Psr\Container\ContainerInterface;
use ReflectionFunctionAbstract;

/**
 * Inject entries from a DI container using the type-hints.
 *
 * Based upon Invoker\ParameterResolver\Container\TypeHintContainerResolver,
 * but allows type hinting `providedParameters`, e.g.:
 * - a callable with the signature `(Foo $foo, Bar $bar)`
 * - gets `$invoker->call(callable, [Foo $far])`
 * - `Foo` is not overwritten from container
 *
 * > note on inheritance: a child class is considered different then the parent for this check.
 *
 * Behaviour of original, e.g.:
 * - a callable with `Foo $foo, Bar $bar`
 * - gets `$invoker->call(callable, [Foo $far])`
 * - but here `Foo` is overwritten by the container
 * - thus the callable gets - depending on setup - an empty object instead of an filled one or with wrong data
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 * @author Michael Becker <michael@bemit.codes> (modified)
 */
class EventDispatcherTypeHintContainerResolver implements ParameterResolver {
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container The container to get entries from.
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function getParameters(
        ReflectionFunctionAbstract $reflection,
        array $providedParameters,
        array $resolvedParameters
    ) {
        $parameters = $reflection->getParameters();

        // Skip parameters already resolved
        if(!empty($resolvedParameters)) {
            $parameters = array_diff_key($parameters, $resolvedParameters);
        }

        $provided_classes = [];
        foreach($providedParameters as $provided_parameter) {
            if(is_object($provided_parameter)) {
                $class = get_class($provided_parameter);
                if('__PHP_Incomplete_Class' !== $class) {
                    // only when not incomplete serialized class
                    $provided_classes[$class] = $provided_parameter;
                }
            }
        }

        foreach($parameters as $index => $parameter) {
            $parameterClass = $parameter->getClass();

            if($parameterClass) {
                if(isset($provided_classes[$parameterClass->name])) {
                    // check if the class was provided hardcoded already, if so - use that instead of controller
                    // allows the event handler to inject any event class and but also type hinting it at consumers, all other type hinted will get resolved by container
                    $resolvedParameters[$index] = $provided_classes[$parameterClass->name];
                } else if($this->container->has($parameterClass->name)) {
                    $resolvedParameters[$index] = $this->container->get($parameterClass->name);
                }
            }
        }

        return $resolvedParameters;
    }
}
