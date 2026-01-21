<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/sym-link', function () {
    Artisan::call('storage:link');
    return 'Storage linked successfully';
});

Route::get('/shield-generate', function () {
    Artisan::call('shield:generate', [
        '--all' => true,
        '--no-interaction' => true,
    ]);
    return 'Shield generated successfully';
});

Route::get('/shield-super-admin', function () {
    Artisan::call('shield:super-admin', [
        '--user' => 1,
        '--no-interaction' => true,
    ]);
    return 'Shield super admin generated successfully';
});
