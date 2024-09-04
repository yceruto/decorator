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

/**
 * Decorates the functionality of a given function.
 */
interface DecoratorInterface
{
    public function decorate(\Closure $func): \Closure;
}
