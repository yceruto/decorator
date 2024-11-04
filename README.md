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

```php
use Yceruto\Decorator\Attribute\DecoratorAttribute;
use Yceruto\Decorator\CallableDecorator;
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
