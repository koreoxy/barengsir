<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Event;

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
        // Rate limiter untuk spam/double-click checkout kasir
        RateLimiter::for('pos-checkout', function (Request $request) {
            return Limit::perMinute(15)->by($request->user()?->id ?: $request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Request checkout terlalu cepat. Harap tunggu beberapa saat sebelum mencoba kembali.'
                    ], 429, $headers);
                });
        });

        // Rate limiter untuk serangan brute force login
        RateLimiter::for('login-attempts', function (Request $request) {
            return Limit::perMinute(5)->by($request->input('email') . $request->ip());
        });

        // Event listeners untuk mencatat log aktivitas pengguna secara otomatis
        Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
            \App\Jobs\LogActivityJob::dispatch([
                'user_id' => $event->user->id,
                'activity' => 'Login',
                'description' => "Pengguna {$event->user->name} ({$event->user->email}) berhasil login ke sistem.",
                'ip_address' => request()->ip() ?? '127.0.0.1',
                'user_agent' => request()->userAgent() ?? 'CLI/System',
            ]);
        });

        Event::listen(\Illuminate\Auth\Events\Logout::class, function ($event) {
            if ($event->user) {
                \App\Jobs\LogActivityJob::dispatch([
                    'user_id' => $event->user->id,
                    'activity' => 'Logout',
                    'description' => "Pengguna {$event->user->name} ({$event->user->email}) keluar dari sistem.",
                    'ip_address' => request()->ip() ?? '127.0.0.1',
                    'user_agent' => request()->userAgent() ?? 'CLI/System',
                ]);
            }
        });

        Event::listen(\Illuminate\Auth\Events\Failed::class, function ($event) {
            \App\Jobs\LogActivityJob::dispatch([
                'user_id' => null,
                'activity' => 'Gagal Login',
                'description' => "Percobaan login gagal untuk email: " . ($event->credentials['email'] ?? 'Tidak diketahui'),
                'ip_address' => request()->ip() ?? '127.0.0.1',
                'user_agent' => request()->userAgent() ?? 'CLI/System',
            ]);
        });
    }
}
