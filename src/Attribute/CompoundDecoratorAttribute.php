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

use Yceruto\Decorator\Decorator\CompoundDecorator;

/**
 * Extend this class to create a reusable set of decorators.
 */
abstract class CompoundDecoratorAttribute implements CompoundDecoratorAttributeInterface
{
    /**
     * @param array<string, mixed> $options
     */
    public function __construct(
        public array $options = [],
    ) {
    }

    public function decoratedBy(): string
    {
        return CompoundDecorator::class;
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
