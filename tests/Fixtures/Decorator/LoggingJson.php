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

use Yceruto\Decorator\Attribute\CompoundDecoratorAttribute;

#[\Attribute(\Attribute::TARGET_METHOD)]
class LoggingJson extends CompoundDecoratorAttribute
{
    public function getAttributes(array $options): array
    {
        return [
            new Logging(),
            new Json(),
        ];
    }
}
