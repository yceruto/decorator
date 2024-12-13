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

namespace Yceruto\Decorator\Attribute;

use Yceruto\Decorator\DecoratorInterface;

/**
 * Abstract class for all decorator attributes with default convention.
 */
abstract class DecoratorAttribute implements DecoratorAttributeInterface
{
    public function decoratedBy(): string
    {
        if ($this instanceof DecoratorInterface) {
            return static::class;
        }

        return static::class.'Decorator';
    }
}
