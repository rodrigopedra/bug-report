<?php

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $http = Http::withToken('access_token')
        ->retry(2, 0, function (Exception $exception, PendingRequest $request) {
            if (! $exception instanceof RequestException || $exception->response->status() !== 401) {
                return false;
            }

            $request->withToken('new_access_token');

            return true;
        })
        ->acceptJson()
        ->asJson()
        ->timeout(15)
        ->throw();

    $currentUrl = \url('/endpoint');

    while (!!$currentUrl) {
        $response = $http->get($currentUrl, [
            'role' => 'member',
            'pagelen' => 20,
        ]);
        $currentUrl = $response->json('next');
        dump($currentUrl, $response->json());
    }

    return true;
});

Route::get('/endpoint', function (Request $request) {
    $page = $request->integer('page', 1);

    if ($page > 5) {
        return ['values' => []];
    }

    return [
        'values' => [],
        'next' => \url('/endpoint') . '?page=' . ($page + 1),
    ];
});
