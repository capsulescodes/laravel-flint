<p align="center"><img src="capsules-laravel-flint-image.svg" width="400px" height="265px" alt="Laravel Flint" /></p>

**Laravel Flint** is a PHP code style fixer for near-minimalists based on **[Laravel Pint](https://github.com/laravel/pint)**.

Flint is built on top of **[PHP-CS-Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer)** and makes it simple to ensure that your code style stays **clean** and **consistent**.

<br>

> [!NOTE]
> This is in active development. New features will be introduced gradually.

<br>

## Features

<br>

### Custom fixers

```
{
    ...
    "rules" : {

        ...
        "CapsulesCodes/method_chaining_indentation" : { "multi-line" : 4 },
        "CapsulesCodes/multiple_lines_after_imports" : { "lines" : 2 },
        "CapsulesCodes/spaces_inside_square_braces" : { "space" : "single" }
        ...

    },
    "fixers" : [ "CapsulesCodes\\Fixers" ]
    ...

}
```
> [!TIP]
> `namespaces` or `imports` are functionnal

<br>

### Empty preset

```
{
    "preset" : "none",
    ...
}
```
> [!TIP]
> the current presets are `laravel`, `none`, `per`, `psr12` and `symfony`

<br>

## Contributing

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.

Please make sure to update tests as appropriate.

The `main` branch is in sync with Laravel Pint. The `fork` branch contains the new features.

<br>

## Credits

- [Taylor Otwell](https://github.com/taylorotwell)
- [Nuno Maduro](https://github.com/nunomaduro)
- [Capsules Codes](https://github.com/capsulescodes)

<br>

## License

[MIT](https://choosealicense.com/licenses/mit/)
