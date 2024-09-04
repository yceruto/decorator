# PHP Function Decorator

![GitHub Actions Workflow Status](https://img.shields.io/github/actions/workflow/status/yceruto/decorator/ci.yml)
![PHP](https://img.shields.io/badge/dynamic/json?url=https%3A%2F%2Fgithub.com%2Fyceruto%2Fdecorator%2Fraw%2Fmain%2Fcomposer.json&query=require.php&label=php)
![Downloads](https://img.shields.io/badge/dynamic/json?url=https%3A%2F%2Fpackagist.org%2Fpackages%2Fyceruto%2Fdecorator.json&query=package.downloads.total&label=downloads)
![GitHub License](https://img.shields.io/github/license/yceruto/decorator)

A function that modifies the behavior of another function or method by wrapping it, without changing its source code.

> [!NOTE]
> Inspired by [Python's decorator](https://peps.python.org/pep-0318/)

## Installation

```bash
composer require yceruto/decorator
```

## Basic usage

```php
use Yceruto\Decorator\Attribute\Decorate;
use Yceruto\Decorator\DecoratorChain;
use Yceruto\Decorator\DecoratorInterface;

#[\Attribute(\Attribute::TARGET_METHOD)]
class MyDecorator extends Decorate implements DecoratorInterface
{
    public function __construct()
    {
        parent::__construct(self::class);
    }

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
    #[MyDecorator]
    public function sayHello(string $name): void
    {
        echo "Hello $name!\n";
    }
}

$greeting = new Greeting();
$decorator = new DecoratorChain();
$decorator->call($greeting->sayHello(...), 'Eve');
```
Output:
```
Do something before
Hello Eve!
Do something after
```

## License

This software is published under the [MIT License](LICENSE)
