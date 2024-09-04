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

namespace Yceruto\Decorator;

use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceLocatorTrait;

class DecoratorLocator implements ContainerInterface
{
    use ServiceLocatorTrait {
        get as private doGet;
    }

    public function get(string $id): DecoratorInterface
    {
        return $this->doGet($id);
    }
}