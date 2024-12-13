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

use Yceruto\Decorator\CompoundDecorator;

/**
 * Extend this class to create a reusable set of decorators.
 */
abstract class Compound extends DecoratorAttribute
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

    /**
     * @param array<string, mixed> $options
     *
     * @return array<DecoratorAttributeInterface>
     */
    abstract public function getDecorators(array $options): array;
}
