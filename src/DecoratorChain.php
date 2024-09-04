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

use Psr\Container\ContainerInterface;
use Yceruto\Decorator\Attribute\Decorate;

class DecoratorChain implements DecoratorInterface
{
    /**
     * @param ContainerInterface $decorators Locator of decorators, keyed by identifier
     */
    public function __construct(
        private readonly ContainerInterface $decorators = new DecoratorLocator([]),
    ) {
    }

    public function call(callable $callable, mixed ...$args): mixed
    {
        return $this->decorate($callable(...))(...$args);
    }

    public function decorate(\Closure $func): \Closure
    {
        foreach ($this->getAttributes($func) as $attribute) {
            if ($attribute instanceof DecoratorInterface && !$this->decorators->has($attribute->id)) {
                $decorator = $attribute;
            } else {
                $decorator = $this->decorators->get($attribute->id);
            }

            $func = $decorator->decorate($func, ...$attribute->options);
        }

        return $func;
    }

    /**
     * @return iterable<Decorate>
     */
    private function getAttributes(\Closure $func): iterable
    {
        $attributes = (new \ReflectionFunction($func))->getAttributes(Decorate::class, \ReflectionAttribute::IS_INSTANCEOF);

        foreach (array_reverse($attributes) as $attribute) {
            yield $attribute->newInstance();
        }
    }
}
