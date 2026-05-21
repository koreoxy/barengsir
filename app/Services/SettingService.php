<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SettingService
{
    /**
     * Mendapatkan cache key berdasarkan branch ID.
     */
    protected static function getCacheKey(int $branchId): string
    {
        return "branch_settings_{$branchId}";
    }

    /**
     * Mengambil semua setting untuk satu cabang dari Cache/Database.
     */
    public static function all(int $branchId): array
    {
        return Cache::rememberForever(self::getCacheKey($branchId), function () use ($branchId) {
            return Setting::where('branch_id', $branchId)
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    /**
     * Mengambil nilai setting berdasarkan key.
     */
    public static function get(string $key, $default = null, ?int $branchId = null)
    {
        $branchId = $branchId ?? session('active_branch_id');
        if (!$branchId) {
            return $default;
        }

        $settings = self::all($branchId);

        return $settings[$key] ?? $default;
    }

    /**
     * Menyimpan atau mengupdate satu setting.
     */
    public static function set(string $key, ?string $value, ?int $branchId = null): void
    {
        $branchId = $branchId ?? session('active_branch_id');
        if (!$branchId) return;

        Setting::updateOrCreate(
            ['key' => $key, 'branch_id' => $branchId],
            ['value' => $value]
        );

        self::clearCache($branchId);
    }

    /**
     * Menyimpan banyak settings sekaligus (Batch Update).
     */
    public static function setMany(array $data, ?int $branchId = null): void
    {
        $branchId = $branchId ?? session('active_branch_id');
        if (!$branchId) return;

        \Illuminate\Support\Facades\DB::transaction(function () use ($data, $branchId) {
            foreach ($data as $key => $value) {
                Setting::updateOrCreate(
                    ['key' => $key, 'branch_id' => $branchId],
                    ['value' => $value]
                );
            }
        });

        self::clearCache($branchId);
    }

    /**
     * Membersihkan cache settings cabang.
     */
    public static function clearCache(int $branchId): void
    {
        Cache::forget(self::getCacheKey($branchId));
    }
}
