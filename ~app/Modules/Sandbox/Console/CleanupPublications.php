<?php

namespace Modules\Sandbox\Console;

use Illuminate\Console\Command;
use Modules\Sandbox\Entities\Publication;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CleanupPublications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publications:cleanup {--dry-run : Preview files to be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up unused publication PDF files';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔍 Scanning for unused publication files...');
        
        $directory = storage_path('app/public/publications');
        
        if (!is_dir($directory)) {
            $this->error('Publications directory does not exist!');
            return 1;
        }

        // Get all PDF files in directory
        $files = glob($directory . '/*.pdf');
        
        // Get all pdf_path from database
        $dbFiles = Publication::pluck('pdf_path')->toArray();
        
        $unusedFiles = [];
        $totalSize = 0;
        
        foreach ($files as $file) {
            $filename = basename($file);
            
            // If file not in database, mark for deletion
            if (!in_array($filename, $dbFiles)) {
                $size = filesize($file);
                $unusedFiles[] = [
                    'path' => $file,
                    'name' => $filename,
                    'size' => $size,
                ];
                $totalSize += $size;
            }
        }
        
        if (empty($unusedFiles)) {
            $this->info('✅ No unused files found.');
            return 0;
        }
        
        $this->warn(sprintf('Found %d unused file(s), Total size: %s', 
            count($unusedFiles), 
            $this->formatBytes($totalSize)
        ));
        
        $this->table(
            ['Filename', 'Size'],
            collect($unusedFiles)->map(function($file) {
                return [
                    $file['name'],
                    $this->formatBytes($file['size'])
                ];
            })
        );
        
        if ($this->option('dry-run')) {
            $this->comment('🔍 Dry run mode - no files were deleted');
            return 0;
        }
        
        if (!$this->confirm('Do you want to delete these files?', true)) {
            $this->comment('Operation cancelled.');
            return 0;
        }
        
        $deletedCount = 0;
        
        foreach ($unusedFiles as $file) {
            if (@unlink($file['path'])) {
                $deletedCount++;
                $this->line(sprintf('✅ Deleted: %s', $file['name']));
            } else {
                $this->error(sprintf('❌ Failed to delete: %s', $file['name']));
            }
        }
        
        Log::info(sprintf('Publication cleanup completed: Deleted %d file(s), freed %s', 
            $deletedCount, 
            $this->formatBytes($totalSize)
        ));
        
        $this->info(sprintf('✨ Cleanup complete! Deleted %d file(s), freed %s', 
            $deletedCount, 
            $this->formatBytes($totalSize)
        ));
        
        return 0;
    }
    
    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
