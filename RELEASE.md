# Release Instructions

1. Pull down the latest changes on the `main` branch
2. Merge `main` branch into `fork` branch
3. Update the version in `config/app.php`
4. Add line `$commandline[0] = "$this->cwd/$commandline[0]";` in `vendor/symfony/process/Process.php` on line [360](https://github.com/laravel-zero/laravel-zero/issues/492) [ Temporary fix ]
5. Compile the binary with

```zsh
php flint app:build
```

6. Remove former `build/flint` file and rename `build/flint.phar` into `build/flint`
6. Commit all changes
7. Push all commits to GitHub
