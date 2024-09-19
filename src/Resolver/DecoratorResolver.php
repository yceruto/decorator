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

namespace Yceruto\Decorator\Resolver;

use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceLocatorTrait;
use Yceruto\Decorator\Attribute\DecoratorAttribute;
use Yceruto\Decorator\DecoratorInterface;

class DecoratorResolver implements DecoratorResolverInterface, ContainerInterface
{
    use ServiceLocatorTrait;

    public function resolve(DecoratorAttribute $metadata): DecoratorInterface
    {
        $id = $metadata->decoratedBy();

        if ($this->has($id)) {
            return $this->get($id);
        }

        if ($metadata::class === $id && $metadata instanceof DecoratorInterface) {
            return $metadata;
        }

        if (class_exists($id)) {
            return new $id();
        }

        return $this->get($id); // let it throw
    }
}
