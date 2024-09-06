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

/**
 * DecoratorMetadata is the abstract class for all decorator metadata attributes.
 */
abstract class DecoratorMetadata
{
    public function decoratedBy(): string
    {
        return static::class.'Decorator';
    }
}
