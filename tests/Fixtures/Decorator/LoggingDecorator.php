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

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Yceruto\Decorator\DecoratorInterface;

final readonly class LoggingDecorator implements DecoratorInterface
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function decorate(\Closure $func, string $level = LogLevel::DEBUG): \Closure
    {
        return function (mixed ...$args) use ($func, $level): mixed {
            $this->logger->log($level, 'Before calling func', ['args' => count($args)]);

            $result = $func(...$args);

            $this->logger->log($level, 'After calling func', ['result' => $result]);

            return $result;
        };
    }
}
