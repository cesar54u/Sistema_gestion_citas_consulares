<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// =============================================
// RECORDATORIOS AUTOMÁTICOS DE CITAS
// Se ejecutan cada hora para enviar recordatorios
// a usuarios con citas aprobadas próximas.
// =============================================
Schedule::command('citas:recordatorios --horas=24')
    ->hourly()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/recordatorios.log'));

Schedule::command('citas:recordatorios --horas=2')
    ->everyThirtyMinutes()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/recordatorios.log'));
