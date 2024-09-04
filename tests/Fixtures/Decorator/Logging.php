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
use Yceruto\Decorator\Attribute\Decorate;

#[\Attribute(\Attribute::TARGET_FUNCTION | \Attribute::TARGET_METHOD)]
final class Logging extends Decorate
{
    public function __construct(
        string $level = LogLevel::DEBUG,
    ) {
        parent::__construct(LoggingDecorator::class, ['level' => $level]);
    }
}
