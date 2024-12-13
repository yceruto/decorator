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

namespace Yceruto\Decorator\Resolver;

use Yceruto\Decorator\Attribute\DecoratorAttributeInterface;
use Yceruto\Decorator\DecoratorInterface;

/**
 * Resolves the decorator linked to a given decorator metadata.
 */
interface DecoratorResolverInterface
{
    public function resolve(DecoratorAttributeInterface $attribute): DecoratorInterface;
}
