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

namespace Yceruto\Decorator\Tests\Fixtures\Handler;

use Yceruto\Decorator\Tests\Fixtures\Decorator\Logging;

#[Logging]
final readonly class InvokableMessageHandler
{
    public function __invoke(Message $message): Message
    {
        return $message;
    }
}
