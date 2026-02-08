<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SystemHealthController extends Controller
{
    public function index()
    {
        // Simple health checks
        $dbStatus = 'unknown';
        try {
            DB::connection()->getPdo();
            $dbStatus = 'connected';
        } catch (\Exception $e) {
            $dbStatus = 'disconnected';
        }

        $cacheStatus = 'unknown';
        try {
            Cache::put('health_check', 'ok', 10);
            if (Cache::get('health_check') === 'ok') {
                $cacheStatus = 'working';
            }
        } catch (\Exception $e) {
            $cacheStatus = 'error';
        }

        // System info (basic)
        $systemInfo = [
            'php_version' => phpversion(),
            'laravel_version' => app()->version(),
            'server_os' => php_uname('s') . ' ' . php_uname('r'),
            'disk_free' => disk_free_space(base_path()),
            'disk_total' => disk_total_space(base_path()),
        ];

        return Inertia::render('SuperAdmin/Health/Index', [
            'status' => [
                'database' => $dbStatus,
                'cache' => $cacheStatus,
            ],
            'system' => $systemInfo,
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
