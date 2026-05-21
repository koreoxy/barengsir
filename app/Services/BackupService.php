<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class BackupService
{
    protected static string $disk = 'local';
    protected static string $backupDir = 'backups';

    /**
     * Get the absolute path to the given binary on Windows.
     */
    protected static function getBinaryPath(string $binary): string
    {
        // On non-Windows platforms, just return the binary name
        if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
            return $binary;
        }

        // Standard direct paths for Windows servers
        $commonPaths = [
            'C:\\xampp\\mysql\\bin\\' . $binary . '.exe',
        ];

        // Search with glob for versions (like MySQL 8.0 or mysql-8.0.30-win64)
        $globPatterns = [
            'C:\\laragon\\bin\\mysql\\mysql-*\\bin\\' . $binary . '.exe',
            'C:\\laragon\\bin\\mysql\\*\\bin\\' . $binary . '.exe',
            'C:\\Program Files\\MySQL\\MySQL Server *\\bin\\' . $binary . '.exe',
            'C:\\Program Files (x86)\\MySQL\\MySQL Server *\\bin\\' . $binary . '.exe',
        ];

        foreach ($commonPaths as $path) {
            if (file_exists($path)) {
                return '"' . $path . '"';
            }
        }

        foreach ($globPatterns as $pattern) {
            $matches = glob($pattern);
            if (!empty($matches)) {
                // Return the last match (usually latest version), quoted for CLI execution
                return '"' . end($matches) . '"';
            }
        }

        // Fallback to global command
        return $binary;
    }

    /**
     * Get list of database backup files with size and date metadata.
     */
    public static function getBackups(): array
    {
        if (!Storage::disk(self::$disk)->exists(self::$backupDir)) {
            Storage::disk(self::$disk)->makeDirectory(self::$backupDir);
        }

        $files = Storage::disk(self::$disk)->files(self::$backupDir);
        $backupList = [];

        foreach ($files as $file) {
            // Only list SQL backup files
            if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                $backupList[] = [
                    'filename'   => basename($file),
                    'path'       => $file,
                    'size'       => Storage::disk(self::$disk)->size($file),
                    'created_at' => date('Y-m-d H:i:s', Storage::disk(self::$disk)->lastModified($file))
                ];
            }
        }

        // Sort descending by date (newest first)
        usort($backupList, function ($a, $b) {
            return strcmp($b['created_at'], $a['created_at']);
        });

        return $backupList;
    }

    /**
     * Generate a new database backup file using mysqldump.
     */
    public static function generateBackup(): string
    {
        $filename = 'backup_' . date('Ymd_His') . '.sql';
        $path = self::$backupDir . '/' . $filename;

        if (!Storage::disk(self::$disk)->exists(self::$backupDir)) {
            Storage::disk(self::$disk)->makeDirectory(self::$backupDir);
        }

        $absolutePath = Storage::disk(self::$disk)->path($path);
        // Normalize backslashes for Windows path execution
        $absolutePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $absolutePath);

        $connectionName = config('database.default');
        
        if ($connectionName !== 'mysql') {
            throw new \Exception("Pencadangan database hanya mendukung koneksi MySQL saat ini.");
        }

        $dbConfig = config("database.connections.{$connectionName}");

        $mysqldump = self::getBinaryPath('mysqldump');

        $username = $dbConfig['username'];
        $password = $dbConfig['password'] !== '' ? '-p' . $dbConfig['password'] : '';
        $host = $dbConfig['host'];
        $port = $dbConfig['port'] ?? '3306';
        $database = $dbConfig['database'];
        $targetFile = '"' . $absolutePath . '"';

        // Build CLI execution command (using native --result-file to prevent Windows line-ending/shell corruption and capture warnings cleanly)
        $command = "{$mysqldump} --user={$username} {$password} --host={$host} --port={$port} --result-file={$targetFile} {$database} 2>&1";

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            // Check if file was partially created and delete it on failure
            if (file_exists($absolutePath)) {
                @unlink($absolutePath);
            }
            $errorOutput = !empty($output) ? implode(' ', $output) : "mysqldump exit code {$returnVar}";
            throw new \Exception("Gagal melakukan pencadangan database: {$errorOutput}");
        }

        return $filename;
    }

    /**
     * Restore database from a specific SQL backup file.
     */
    public static function restoreDatabase(string $filename): void
    {
        $path = self::$backupDir . '/' . $filename;
        $absolutePath = Storage::disk(self::$disk)->path($path);
        // Normalize backslashes for Windows path execution
        $absolutePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $absolutePath);

        if (!Storage::disk(self::$disk)->exists($path)) {
            throw new \Exception("File backup '{$filename}' tidak ditemukan di server.");
        }

        $connectionName = config('database.default');
        if ($connectionName !== 'mysql') {
            throw new \Exception("Pemulihan database hanya mendukung koneksi MySQL saat ini.");
        }

        $dbConfig = config("database.connections.{$connectionName}");

        $mysql = self::getBinaryPath('mysql');

        $username = $dbConfig['username'];
        $password = $dbConfig['password'] !== '' ? '-p' . $dbConfig['password'] : '';
        $host = $dbConfig['host'];
        $port = $dbConfig['port'] ?? '3306';
        $database = $dbConfig['database'];
        $sourceFile = '"' . $absolutePath . '"';

        // 1. Masuk ke Maintenance Mode
        Artisan::call('down', [
            '--secret' => 'superadmin-bypass-key',
            '--refresh' => 10,
            '--render' => 'errors::503'
        ]);

        try {
            // 2. Jalankan mysql import (capturing stderr 2>&1 to diagnose exceptions)
            $command = "{$mysql} --user={$username} {$password} --host={$host} --port={$port} {$database} < {$sourceFile} 2>&1";
            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                $errorOutput = !empty($output) ? implode(' ', $output) : "mysql import exit code {$returnVar}";
                throw new \Exception("Gagal memulihkan database: {$errorOutput}");
            }
        } finally {
            // 3. Hidupkan kembali aplikasi dari Maintenance Mode
            Artisan::call('up');
        }
    }
}
