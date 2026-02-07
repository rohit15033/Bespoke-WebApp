<?php

require __DIR__.'/vendor/autoload.php';

$app = require __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

$service = new \App\Services\InstagramService();
$result = $service->fetchRecentMedia();
file_put_contents('debug_output.txt', print_r($result, true));
// print_r($result);
