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

namespace Yceruto\Decorator\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Yceruto\Decorator\DecoratorChain;
use Yceruto\Decorator\Tests\Fixtures\Controller\CreateTaskController;
use Yceruto\Decorator\Tests\Fixtures\Decorator\JsonDecorator;
use Yceruto\Decorator\Tests\Fixtures\Decorator\Logging;
use Yceruto\Decorator\Tests\Fixtures\Decorator\LoggingDecorator;
use Yceruto\Decorator\Tests\Fixtures\Handler\Message;
use Yceruto\Decorator\Tests\Fixtures\Handler\MessageHandler;
use Yceruto\Decorator\Tests\Fixtures\Logger\TestLogger;

#[CoversClass(DecoratorChain::class)]
class DecoratorChainTest extends TestCase
{
    private TestLogger $logger;
    private DecoratorChain $decorator;

    protected function setUp(): void
    {
        $this->logger = new TestLogger();
        $this->decorator = DecoratorChain::from([
            JsonDecorator::class => fn () => new JsonDecorator(),
            LoggingDecorator::class => fn () => new LoggingDecorator($this->logger),
        ]);
    }

    public function testTopDecoratedFunc(): void
    {
        $func = $this->decorator->decorate(MessageHandler::handle2(...));
        $reflection = new \ReflectionFunction($func);

        $this->assertSame(LoggingDecorator::class, $reflection->getClosureThis()::class);
    }

    public function testNestedDecorators(): void
    {
        $controller = new CreateTaskController();

        $result = $this->decorator->call($controller);

        $expectedRecords = [
            [
                'level' => 'debug',
                'message' => 'Before calling func',
                'context' => ['args' => 0],
            ],
            [
                'level' => 'debug',
                'message' => 'After calling func',
                'context' => ['result' => '{"id":1,"description":"Take a break!"}'],
            ],
        ];

        $this->assertSame('{"id":1,"description":"Take a break!"}', $result);
        $this->assertSame($expectedRecords, $this->logger->records);
    }

    #[DataProvider('getCallableProvider')]
    public function testDecorate(callable $callable, array $args, mixed $expectedResult, array $expectedRecords): void
    {
        $result = $this->decorator->call($callable, ...$args);

        $this->assertSame($expectedResult, $result);
        $this->assertSame($expectedRecords, $this->logger->records);
    }

    public static function getCallableProvider(): iterable
    {
        #[Logging]
        function foo(string $bar): string
        {
            return $bar;
        }

        yield 'function' => [
            foo(...), ['bar'], 'bar', [
                [
                    'level' => 'debug',
                    'message' => 'Before calling func',
                    'context' => ['args' => 1],
                ],
                [
                    'level' => 'debug',
                    'message' => 'After calling func',
                    'context' => ['result' => 'bar'],
                ],
            ],
        ];

        $message = new Message();
        $handler = new MessageHandler();

        yield 'invokable_object' => [
            $handler, [$message], $message, [
                [
                    'level' => 'debug',
                    'message' => 'Before calling func',
                    'context' => ['args' => 1],
                ],
                [
                    'level' => 'debug',
                    'message' => 'After calling func',
                    'context' => ['result' => $message],
                ],
            ],
        ];

        yield 'array' => [
            [$handler, 'handle1'], [$message], $message, [
                [
                    'level' => 'info',
                    'message' => 'Before calling func',
                    'context' => ['args' => 1],
                ],
                [
                    'level' => 'info',
                    'message' => 'After calling func',
                    'context' => ['result' => $message],
                ],
            ],
        ];

        yield 'array_static_method' => [
            [$handler::class, 'handle2'], [$message], $message, [
                [
                    'level' => 'debug',
                    'message' => 'Before calling func',
                    'context' => ['args' => 1],
                ],
                [
                    'level' => 'debug',
                    'message' => 'After calling func',
                    'context' => ['result' => $message],
                ],
            ],
        ];

        yield 'first_class_static_method' => [
            $handler::handle2(...), [$message], $message, [
                [
                    'level' => 'debug',
                    'message' => 'Before calling func',
                    'context' => ['args' => 1],
                ],
                [
                    'level' => 'debug',
                    'message' => 'After calling func',
                    'context' => ['result' => $message],
                ],
            ],
        ];
    }
}
