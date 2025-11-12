<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StorageLinkCheckCommand extends Command
{
    // NOTE: ใช้ --details แทน --verbose เพราะ Laravel/Symfony มี --verbose อยู่แล้ว
    protected $signature = 'diagnose:storage-link {--details}';
    protected $description = 'ตรวจสอบสถานะ storage link / พาธการอัปโหลด (public/storage -> storage/app/public) และสิทธิ์การเขียน';

    public function handle(): int
    {
    $publicStorageLink = public_path('storage');
    $target = storage_path('app/public');
    // Alternative public root (this project serves index.php from repo root, not ~app/public)
    $altPublicRoot = dirname(base_path());
    $altPublicStorageLink = $altPublicRoot . DIRECTORY_SEPARATOR . 'storage';

        $this->info('--- Storage Link Diagnostics ---');
    $this->line('Public link path      : ' . $publicStorageLink);
        $this->line('Expected target path  : ' . $target);
    $this->line('Alt public link path  : ' . $altPublicStorageLink);

        // Existence checks
    $linkExists = file_exists($publicStorageLink);
    $altLinkExists = file_exists($altPublicStorageLink);
        $targetExists = file_exists($target);
        $this->line('Link exists?          : ' . ($linkExists ? 'YES' : 'NO'));
    $this->line('Alt link exists?      : ' . ($altLinkExists ? 'YES' : 'NO'));
        $this->line('Target exists?        : ' . ($targetExists ? 'YES' : 'NO'));

        // Is symlink?
    $isSymlink = is_link($publicStorageLink);
    $isAltSymlink = is_link($altPublicStorageLink);
        $this->line('Is symlink?           : ' . ($isSymlink ? 'YES' : 'NO'));
    $this->line('Alt is symlink?       : ' . ($isAltSymlink ? 'YES' : 'NO'));

        // Resolve real path
    $resolved = realpath($publicStorageLink) ?: 'N/A';
    $resolvedAlt = realpath($altPublicStorageLink) ?: 'N/A';
        $this->line('Real path(link)       : ' . $resolved);
    $this->line('Real path(alt link)   : ' . $resolvedAlt);
        $realTarget = realpath($target) ?: 'N/A';
        $this->line('Real path(target)     : ' . $realTarget);

        // Compare
    $pointsCorrectly = ($resolved !== 'N/A' && $realTarget !== 'N/A' && $resolved === $realTarget);
    $altPointsCorrectly = ($resolvedAlt !== 'N/A' && $realTarget !== 'N/A' && $resolvedAlt === $realTarget);
        $this->line('Points to target?     : ' . ($pointsCorrectly ? 'YES' : 'NO'));
    $this->line('Alt points to target? : ' . ($altPointsCorrectly ? 'YES' : 'NO'));

        // Writability
    $linkWritable = is_writable($publicStorageLink);
    $altLinkWritable = is_writable($altPublicStorageLink);
        $targetWritable = is_writable($target);
        $this->line('Writable (link path)  : ' . ($linkWritable ? 'YES' : 'NO'));
    $this->line('Writable (alt link)   : ' . ($altLinkWritable ? 'YES' : 'NO'));
        $this->line('Writable (target)     : ' . ($targetWritable ? 'YES' : 'NO'));

        // Test write file (non-destructive)
        $testFilename = '.__storage_link_test_' . uniqid() . '.txt';
        $testFullPath = $target . DIRECTORY_SEPARATOR . $testFilename;
        $writeOk = false;
        $errorMsg = '';
        if ($targetWritable) {
            try {
                $bytes = @file_put_contents($testFullPath, 'test:' . date('c'));
                if ($bytes !== false) {
                    $writeOk = true;
                    @unlink($testFullPath);
                } else {
                    $errorMsg = 'file_put_contents returned false';
                }
            } catch (\Throwable $e) {
                $errorMsg = $e->getMessage();
            }
        }
        $this->line('Write test to target  : ' . ($writeOk ? 'SUCCESS' : 'FAIL')); 
        if (!$writeOk && $errorMsg) {
            $this->warn('  Reason: ' . $errorMsg);
        }

        // Common misconfiguration hints
        $this->info(PHP_EOL . '--- Hints ---');
        if (!$linkExists && !$altLinkExists) {
            $this->warn('Link ไม่พบ: ต้องสร้างด้วย artisan storage:link หรือ mklink /J (Windows)');
        } elseif (!$isSymlink) {
            $this->warn('Public/storage เป็นโฟลเดอร์จริง ไม่ใช่ symlink: พิจารณา backup แล้วสร้างเป็น link');
        }
        if ($linkExists && $isSymlink && !$pointsCorrectly) {
            $this->warn('Symlink ชี้ไปผิดตำแหน่ง: ลบแล้วสร้างใหม่ให้ตรง target');
        }
        if ($altLinkExists && $isAltSymlink && !$altPointsCorrectly) {
            $this->warn('Alt Symlink ชี้ไปผิดตำแหน่ง: ลบแล้วสร้างใหม่ให้ตรง target');
        }
        if (!$targetWritable) {
            $this->warn('Target ไม่สามารถเขียน: ตรวจสิทธิ์ (IIS_IUSRS / AppPool Identity)');
        }
        if ($writeOk && !$pointsCorrectly) {
            $this->warn('เขียนได้แต่ path เสิร์ฟไฟล์อาจผิด: ตรวจสอบ URL ที่ใช้ Storage::url()');
        }

        if ($this->option('details')) {
            $this->info(PHP_EOL . '--- Verbose Checks ---');
            $this->line('PHP version           : ' . PHP_VERSION);
            $this->line('OS                    : ' . PHP_OS_FAMILY);
            $this->line('Directory separator   : ' . DIRECTORY_SEPARATOR);
            $this->line('storage_path(app)     : ' . storage_path());
            $this->line('storage_path(app/public): ' . $target);
            $this->line('public_path()         : ' . public_path());
        }

        $this->info(PHP_EOL . 'Done.');
        return 0;
    }
}
