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
class CallableDecorator implements DecoratorInterface
{
    public function __construct(
        private readonly DecoratorResolverInterface $resolver = new DecoratorResolver([]),
    ) {
    }

    public function call(callable $callable, mixed ...$args): mixed
    {
        return $this->decorate($callable(...))(...$args);
    }

    public function decorate(\Closure $func): \Closure
    {
        foreach ($this->getAttributes($func) as $attribute) {
            $func = $this->resolver->resolve($attribute)->decorate($func, $attribute);
        }

        return $func;
    }

    /**
     * @return iterable<DecoratorAttribute>
     */
    private function getAttributes(\Closure $func): iterable
    {
        $attributes = (new \ReflectionFunction($func))->getAttributes(DecoratorAttribute::class, \ReflectionAttribute::IS_INSTANCEOF);

        foreach (array_reverse($attributes) as $attribute) {
            yield $attribute->newInstance();
        }
    }
}
