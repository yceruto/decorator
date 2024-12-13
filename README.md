# PHP Callable Decorator

![GitHub Actions Workflow Status](https://img.shields.io/github/actions/workflow/status/yceruto/decorator/ci.yml)
![Version](https://img.shields.io/badge/dynamic/json?url=https%3A%2F%2Frepo.packagist.org%2Fp2%2Fyceruto%2Fdecorator.json&query=%24.packages%5B%22yceruto%2Fdecorator%22%5D%5B0%5D.version&label=version)
![PHP](https://img.shields.io/badge/dynamic/json?url=https%3A%2F%2Fgithub.com%2Fyceruto%2Fdecorator%2Fraw%2Fmain%2Fcomposer.json&query=require.php&label=php)
![GitHub License](https://img.shields.io/github/license/yceruto/decorator)

> [!NOTE]
> Inspired by [Python's decorator](https://peps.python.org/pep-0318/)

This library implements the [Decorator Pattern](https://en.wikipedia.org/wiki/Decorator_pattern) around 
any [PHP callable](https://www.php.net/manual/en/language.types.callable.php), allowing you to:
 * Execute logic before or after a callable is executed
 * Skip the execution of a callable by returning earlier
 * Modify the result of a callable

## Installation

```bash
composer require yceruto/decorator
```

## Usage

### Example 1 - Minimalistic

```php
use Yceruto\Decorator\Attribute\DecoratorAttribute;
use Yceruto\Decorator\CallableDecorator;
use Yceruto\Decorator\DecoratorInterface;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Debug extends DecoratorAttribute implements DecoratorInterface
{
    public function decorate(\Closure $func): \Closure
    {
        return function (mixed ...$args) use ($func): mixed
        {
            echo "Do something before\n";

            $result = $func(...$args);

            echo "Do something after\n";

            return $result;
        };
    }
}

class Greeting
{
    #[Debug]
    public function sayHello(string $name): void
    {
        echo "Hello $name!\n";
    }
}

$greeting = new Greeting();
$decorator = new CallableDecorator();
$decorator->call($greeting->sayHello(...), 'John');
```
Output:
```
Do something before
Hello John!
Do something after
```

### Example 2 - Split decorator metadata from its implementation

You can separate the decorator attribute from its implementation whenever necessary:

```php
use Yceruto\Decorator\Attribute\DecoratorAttribute;
use Yceruto\Decorator\DecoratorInterface;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Debug extends DecoratorAttribute
{
}

class DebugDecorator implements DecoratorInterface
{
    public function decorate(\Closure $func): \Closure
    {
        return function (mixed ...$args) use ($func): mixed
        {
            echo "Do something before\n";

            $result = $func(...$args);

            echo "Do something after\n";

            return $result;
        };
    }
}
```

The `DecoratorAttribute` automatically links to its corresponding decorator class if the decorator is in the same 
directory, shares the same base name, and ends with the `*Decorator` suffix. If these conditions are not met, you must 
define the `decoratedBy()` method to establish the link manually.

### Example 3 - Decorating invokable objects

In this example, the `Debug` attribute is used to decorate an invokable object. The decorator logic will be applied 
when the `__invoke()` method is called.

```php
#[Debug]
class Greeting
{
    public function __invoke(string $name): void
    {
        echo "Hello $name!\n";
    }
}
```

> [!IMPORTANT]
> The attribute will only be collected if there are no other decorator attributes defined on the `__invoke` method.

### Example 4 - Adding configuration to your decorator

Add options through your attribute `__construct()` method, and pass them at the end of your `decorate()` method:

```php
use Yceruto\Decorator\Attribute\DecoratorAttribute;
use Yceruto\Decorator\DecoratorInterface;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Debug extends DecoratorAttribute implements DecoratorInterface
{
    public function __construct(
        private readonly string $prefix = '',
    ) {
    }

    public function decorate(\Closure $func, self $debug = new self()): \Closure
    {
        return function (mixed ...$args) use ($func, $debug): mixed
        {
            echo $debug->prefix."Do something before\n";

            $result = $func(...$args);

            echo $debug->prefix."Do something after\n";

            return $result;
        };
    }
}

class Greeting
{
    #[Debug(prefix: 'Greeting: ')]
    public function sayHello(string $name): void
    {
        echo "Hello $name!\n";
    }
}
```
Output:
```
Greeting: Do something before
Hello John!
Greeting: Do something after
```

## Frameworks Integration

* Symfony: https://github.com/yceruto/decorator-bundle

## Decorators

### Compound

To create a reusable set of decorators, extend the `Compound` class:

```php
use Yceruto\Decorator\Attribute\Compound;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Greetings extends Compound
{
    /**
     * @return array<DecoratorAttribute>
     */
    public function getDecorators(array $options): array
    {
        return [
            new Hello(),
            new Welcome(),
            // ...
        ];
    }
}

class Greeting
{
    #[Greetings]
    public function __invoke(): void
    {
        // ...
    }
}
```

When the `Greeting::__invoke()` method is decorated, the `Hello` and `Welcome` decorator attributes will be applied 
in the specified order. This is equivalent to directly defining `#[Hello]` and `#[Welcome]` on this method.

## License

This software is published under the [MIT License](LICENSE)
