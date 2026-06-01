<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('ris:about', function () {
    $this->info('RIS V1 Enterprise Laravel 12 API');
});
