<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\BackupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BackupController extends Controller
{
    /**
     * Display a list of all database backups.
     */
    public function index()
    {
        try {
            $backups = BackupService::getBackups();
        } catch (\Exception $e) {
            $backups = [];
            session()->now('error', 'Gagal memuat list backup: ' . $e->getMessage());
        }

        return view('superadmin.backup.index', compact('backups'));
    }

    /**
     * Trigger a new database backup.
     */
    public function store()
    {
        try {
            $filename = BackupService::generateBackup();
            return redirect()->back()->with('success', "Database berhasil dicadangkan dengan nama file: {$filename}");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mencadangkan database: ' . $e->getMessage());
        }
    }

    /**
     * Download a specific backup file securely.
     */
    public function download(string $filename)
    {
        $path = 'backups/' . $filename;

        if (!Storage::disk('local')->exists($path)) {
            return redirect()->back()->with('error', 'File backup tidak ditemukan.');
        }

        return Storage::disk('local')->download($path, $filename, [
            'Content-Type' => 'application/sql',
        ]);
    }

    /**
     * Delete a specific database backup file.
     */
    public function destroy(string $filename)
    {
        $path = 'backups/' . $filename;

        if (Storage::disk('local')->exists($path)) {
            Storage::disk('local')->delete($path);
            return redirect()->back()->with('success', 'File backup berhasil dihapus dari server.');
        }

        return redirect()->back()->with('error', 'File backup tidak ditemukan.');
    }

    /**
     * Restore database from a specific backup SQL file.
     */
    public function restore(string $filename)
    {
        try {
            BackupService::restoreDatabase($filename);
            return redirect()->route('superadmin.backups.index')->with('success', 'Database berhasil dipulihkan ke snapshot sebelumnya.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memulihkan database: ' . $e->getMessage());
        }
    }
}
