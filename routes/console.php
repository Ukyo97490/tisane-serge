<?php

use App\Console\Commands\SendOrderReminders;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Rappels de commandes : toutes les 30 minutes
Schedule::command(SendOrderReminders::class)->everyThirtyMinutes();
