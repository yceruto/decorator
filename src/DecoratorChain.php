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
use Symfony\Contracts\Service\ServiceLocatorTrait;
use Yceruto\Decorator\Attribute\Decorate;

class DecoratorChain implements DecoratorInterface
{
    /**
     * @param ContainerInterface $decorators Locator of decorators, keyed by service id
     */
    public function __construct(
        private readonly ContainerInterface $decorators,
    ) {
    }

    /**
     * @param array<string, callable(): DecoratorInterface> $decorators
     */
    public static function from(array $decorators): self
    {
        return new self(new class($decorators) implements ContainerInterface {
            use ServiceLocatorTrait;
        });
    }

    public function call(callable $callable, mixed ...$args): mixed
    {
        return $this->decorate($callable(...))(...$args);
    }

    public function decorate(\Closure $func): \Closure
    {
        foreach ($this->getAttributes($func) as $attribute) {
            $func = $this->decorators->get($attribute->id)->decorate($func, ...$attribute->options);
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
