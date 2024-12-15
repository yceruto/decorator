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

namespace Yceruto\Decorator\Decorator;

use Yceruto\Decorator\Attribute\CompoundDecoratorAttribute;
use Yceruto\Decorator\Attribute\CompoundDecoratorAttributeInterface;
use Yceruto\Decorator\DecoratorInterface;
use Yceruto\Decorator\Resolver\DecoratorResolverInterface;

class CompoundDecorator implements DecoratorInterface
{
    public function __construct(
        private readonly DecoratorResolverInterface $resolver,
    ) {
    }

    public function decorate(\Closure $func, CompoundDecoratorAttributeInterface $compound = new EmptyCompound()): \Closure
    {
        $attributes = $compound->getAttributes($compound->getOptions());

        foreach (array_reverse($attributes) as $attribute) {
            $func = $this->resolver->resolve($attribute)->decorate($func, $attribute);
        }

        return $func;
    }
}

/**
 * @internal
 */
final class EmptyCompound extends CompoundDecoratorAttribute
{
    public function getAttributes(array $options): array
    {
        return [];
    }
}
