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

namespace Yceruto\Decorator\Tests\Fixtures\Decorator;

use Psr\Log\LogLevel;
use Yceruto\Decorator\Attribute\DecoratorAttribute;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::TARGET_FUNCTION)]
final class Logging extends DecoratorAttribute
{
    public function __construct(
        public readonly string $level = LogLevel::DEBUG,
    ) {
    }
}
