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

namespace Yceruto\Decorator\Attribute;

/**
 * Specifies the decorator to wrap a function or method.
 */
#[\Attribute(\Attribute::TARGET_FUNCTION | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class Decorate
{
    /**
     * @param string               $id      The decorator service id
     * @param array<string, mixed> $options The decorator options to pass through
     */
    public function __construct(
        public string $id,
        public array $options = [],
    ) {
    }
}
