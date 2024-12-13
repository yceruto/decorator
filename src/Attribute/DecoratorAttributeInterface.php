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
 * Interface for all decorator attributes.
 */
interface DecoratorAttributeInterface
{
    /**
     * @return string the class or id of the decorator associated with this attribute
     */
    public function decoratedBy(): string;
}
