# Release Instructions

1. Pull down the latest changes on the `main` branch
2. Update the version in [`config/app.php`](./config/app.php)
3. Compile the binary with

```zsh
php flint app:build
```

4. Commit all changes
5. Push all commits to GitHub
