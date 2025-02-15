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

interface CallableDecoratorInterface
{
    /**
     * @throws \ReflectionException
     */
    public function call(callable $callable, mixed ...$args): mixed;
}
