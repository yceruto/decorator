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
 * Extend this interface to create a reusable set of decorators.
 */
interface CompoundDecoratorAttributeInterface extends DecoratorAttributeInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array;

    /**
     * @param array<string, mixed> $options
     *
     * @return array<DecoratorAttributeInterface>
     */
    public function getAttributes(array $options): array;
}
