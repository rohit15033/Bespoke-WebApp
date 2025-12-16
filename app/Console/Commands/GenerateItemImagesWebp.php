<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class GenerateItemImagesWebp extends Command
{
    protected $signature = 'images:webp:items-images';
    protected $description = 'Generate high-quality WebP images for items_images';

    public function handle()
    {
        $sourceDir = storage_path('app/public/items_images');
        $targetDir = storage_path('app/public/items_images/webp');

        if (!File::exists($sourceDir)) {
            $this->error("Source directory not found: {$sourceDir}");
            return Command::FAILURE;
        }

        if (!File::exists($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        $files = File::files($sourceDir);
        $this->info('Found ' . count($files) . ' images');

        $manager = new ImageManager(new Driver());

        $generated = 0;
        $skipped   = 0;
        $failed    = 0;

        foreach ($files as $file) {
            try {
                $ext = strtolower($file->getExtension());

                if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                    continue;
                }

                $base = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                $webpPath = $targetDir . '/' . $base . '.webp';

                if (File::exists($webpPath)) {
                    $this->line("Skipped: {$base}");
                    $skipped++;
                    continue;
                }

                $image = $manager->read($file->getRealPath());

                // CORRECT v3 METHOD
                $image->scale(width: 1400);

                // High-quality WebP
                $image->toWebp(92)->save($webpPath);

                // Explicit cleanup
                unset($image);
                gc_collect_cycles();

                $this->line("Generated: {$base}.webp");
                $generated++;

            } catch (\Throwable $e) {
                $failed++;
                $this->error("Failed: {$file->getFilename()} — {$e->getMessage()}");
            }
        }

        $this->newLine();
        $this->info("Done.");
        $this->info("Generated: {$generated}");
        $this->info("Skipped:   {$skipped}");
        $this->info("Failed:    {$failed}");

        return Command::SUCCESS;
    }
}
