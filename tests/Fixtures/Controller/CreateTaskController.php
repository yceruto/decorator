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

namespace Yceruto\Decorator\Tests\Fixtures\Controller;

use Yceruto\Decorator\Tests\Fixtures\Decorator\Json;
use Yceruto\Decorator\Tests\Fixtures\Decorator\Logging;

final readonly class CreateTaskController
{
    #[Logging]
    #[Json]
    public function __invoke(): Task
    {
        return new Task(1, 'Take a break!');
    }
}
