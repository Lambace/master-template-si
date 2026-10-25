<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use ZipArchive;

class GeneratorController extends Controller
{
    public function downloadZip()
    {
        $zipFileName = 'master-template-si-' . date('Y-m-d-His') . '.zip';
        $zipPath = storage_path('app/public/' . $zipFileName);

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            return back()->with('error', 'Gagal membuat file ZIP.');
        }

        // Folder dan file yang INGIN kita masukkan ke dalam ZIP
        $filesToAdd = [
            'app' => base_path('app'),
            'routes' => base_path('routes'),
            'resources/views' => base_path('resources/views'),
            'database/migrations' => base_path('database/migrations'),
            'database/seeders' => base_path('database/seeders'),
            'public' => base_path('public'),
            'composer.json' => base_path('composer.json'),
            'composer.lock' => base_path('composer.lock'),
            '.env.example' => base_path('.env.example'),
            'README.md' => base_path('README.md'),
        ];

        // Folder yang HARUS DIABAIKAN (agar ZIP tidak raksasa)
        $excludeDirs = ['vendor', 'node_modules', 'storage', 'bootstrap/cache', '.git'];

        foreach ($filesToAdd as $alias => $realPath) {
            if (File::isDirectory($realPath)) {
                $this->addFolderToZip($zip, $realPath, $alias, $excludeDirs);
            } elseif (File::isFile($realPath)) {
                $zip->addFile($realPath, basename($realPath));
            }
        }

        $zip->close();

        // Pastikan folder storage/app/public ada
        if (!File::exists(storage_path('app/public'))) {
            File::makeDirectory(storage_path('app/public'), 0755, true);
        }

        // Download file dan hapus setelahnya
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    // Fungsi rekursif untuk menambahkan folder ke dalam ZIP
    private function addFolderToZip($zip, $realPath, $zipPath, $excludeDirs)
    {
        $files = File::allFiles($realPath);
        foreach ($files as $file) {
            $filePath = $file->getRealPath();
            
            // Cek apakah file berada di dalam folder yang dikecualikan
            $skip = false;
            foreach ($excludeDirs as $exclude) {
                if (strpos($filePath, DIRECTORY_SEPARATOR . $exclude . DIRECTORY_SEPARATOR) !== false || 
                    strpos($filePath, DIRECTORY_SEPARATOR . $exclude) === 0) {
                    $skip = true;
                    break;
                }
            }

            if (!$skip) {
                $relativePath = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $filePath);
                // Sesuaikan path di dalam ZIP agar sesuai alias (misal: app/Http/...)
                $relativePath = preg_replace('/^' . preg_quote(File::basename($realPath), '/') . '/', $zipPath, $relativePath);
                
                $zip->addFile($filePath, $relativePath);
            }
        }
    }
}