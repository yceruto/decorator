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
use Yceruto\Decorator\Attribute\DecoratorAttribute;
use Yceruto\Decorator\DecoratorInterface;
use Yceruto\Decorator\Resolver\DecoratorResolverInterface;

readonly class CompoundDecorator implements DecoratorInterface
{
    public function __construct(
        private DecoratorResolverInterface $resolver,
    ) {
    }

    /**
     * @param CompoundDecoratorAttribute $attribute
     */
    public function decorate(\Closure $func, DecoratorAttribute $attribute): \Closure
    {
        $attributes = $attribute->getAttributes($attribute->getOptions());

        foreach (array_reverse($attributes) as $attr) {
            $func = $this->resolver->resolve($attr)->decorate($func, $attr);
        }

        return $func;
    }
}
