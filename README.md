https://github.com/laravel/framework/issues/53137

1. Clone the repo
2. Run `composer install`
3. Copy `.env.example` to `.env`
4. Run `php artisan key:generate`
5. Configure any other relevant `.env` variables
6. Serve the app (see note below)
7. Navigate to `/`

### Note: 

As the app is dispatching a request to itself, running `php artisan serve` won't work,
as `php artisan serve` only processes one request at a time

Use something Laravel Valet, to serve it from an web server that can handle multiple requests.
