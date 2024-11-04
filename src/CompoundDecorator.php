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

use Yceruto\Decorator\Attribute\Compound;
use Yceruto\Decorator\Resolver\DecoratorResolver;
use Yceruto\Decorator\Resolver\DecoratorResolverInterface;

class CompoundDecorator implements DecoratorInterface
{
    public function __construct(
        private readonly DecoratorResolverInterface $resolver = new DecoratorResolver([]),
    ) {
    }

    public function decorate(\Closure $func, Compound $compound = new DefaultCompound()): \Closure
    {
        $attributes = $compound->getDecorators($compound->options);

        foreach (array_reverse($attributes) as $attribute) {
            $func = $this->resolver->resolve($attribute)->decorate($func, $attribute);
        }

        return $func;
    }
}

/**
 * @internal
 */
final class DefaultCompound extends Compound
{
    public function getDecorators(array $options): array
    {
        return [];
    }
}
