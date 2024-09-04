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

use Yceruto\Decorator\Attribute\Decorate;
use Yceruto\Decorator\DecoratorInterface;

#[\Attribute(\Attribute::TARGET_METHOD)]
final class Json extends Decorate implements DecoratorInterface
{
    public function __construct()
    {
        parent::__construct(self::class);
    }

    public function decorate(\Closure $func): \Closure
    {
        return static function (mixed ...$args) use ($func): string {
            $result = $func(...$args);

            return json_encode($result, JSON_THROW_ON_ERROR);
        };
    }
}