<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('configuracion_correos')) {
                $config = \App\Models\ConfiguracionCorreo::first();
                
                if ($config && $config->smtp_username && $config->smtp_password) {
                    config([
                        'mail.default'                      => 'smtp',
                        'mail.mailers.smtp.host'            => 'smtp.gmail.com',
                        'mail.mailers.smtp.port'            => 587,
                        'mail.mailers.smtp.encryption'      => 'tls',
                        'mail.mailers.smtp.username'        => $config->smtp_username,
                        'mail.mailers.smtp.password'        => $config->smtp_password,
                        'mail.from.address'                 => $config->from_address ?: $config->smtp_username,
                        'mail.from.name'                    => $config->from_name ?: 'Consulado España Maracay',
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Ignore DB exception during setup/migrations
        }
    }
}
