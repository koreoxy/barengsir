<?php

if (!function_exists('setting')) {
    /**
     * Helper untuk mendapatkan konfigurasi sistem POS.
     */
    function setting(string $key, $default = null) {
        return \App\Services\SettingService::get($key, $default);
    }
}
