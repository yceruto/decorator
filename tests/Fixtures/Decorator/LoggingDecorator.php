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
use Yceruto\Decorator\AbstractDecorator;
use Yceruto\Decorator\Attribute\DecoratorAttribute;

final class LoggingDecorator extends AbstractDecorator
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * @param Logging $attribute
     */
    protected function call(DecoratorAttribute $attribute, \Closure $func, mixed ...$args): mixed
    {
        $this->logger->log($attribute->level, 'Before calling func', ['args' => count($args)]);

        $result = $func(...$args);

        $this->logger->log($attribute->level, 'After calling func', ['result' => $result]);

        return $result;
    }
}
