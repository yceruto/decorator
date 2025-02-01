<?php

declare(strict_types=1);

/*
 * This file is part of Decorator package.
 *
 * (c) Yonel Ceruto <open@yceruto.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yceruto\Decorator;

use Yceruto\Decorator\Attribute\DecoratorAttribute;
use Yceruto\Decorator\Resolver\DecoratorResolver;
use Yceruto\Decorator\Resolver\DecoratorResolverInterface;

/**
 * Wraps a callable with all the decorators linked to it.
 */
final readonly class CallableDecorator implements CallableDecoratorInterface
{
    public function __construct(
        private DecoratorResolverInterface $resolver = new DecoratorResolver([]),
    ) {
    }

    /**
     * @throws \ReflectionException
     */
    public function call(callable $callable, mixed ...$args): mixed
    {
        return $this->decorate($callable(...))(...$args);
    }

    /**
     * @throws \ReflectionException
     */
    public function decorate(\Closure $func): \Closure
    {
        foreach ($this->getAttributes($func) as $attribute) {
            $func = $this->resolver->resolve($attribute)->decorate($func, $attribute);
        }

        return $func;
    }

    /**
     * Extract all decorator attributes from the given callable.
     *
     * @return iterable<DecoratorAttributeInterface>
     *
     * @throws \ReflectionException
     */
    private function getAttributes(\Closure $func): iterable
    {
        $function = new \ReflectionFunction($func);

        $attributes = $function->getAttributes(DecoratorAttribute::class, \ReflectionAttribute::IS_INSTANCEOF);

        if (!$attributes && '__invoke' === $function->getName() && $class = $function->getClosureCalledClass()) {
            $attributes = $class->getAttributes(DecoratorAttribute::class, \ReflectionAttribute::IS_INSTANCEOF);
        }

        foreach (array_reverse($attributes) as $attribute) {
            yield $attribute->newInstance();
        }
    }
}
