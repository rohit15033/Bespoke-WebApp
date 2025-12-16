<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class GenerateItemImagesWebp extends Command
{
    protected $signature = 'images:webp:items-images';
    protected $description = 'Generate WebP versions for items_images';

    public function handle()
    {
        $sourceDir = public_path('storage/items_images');
        $targetDir = public_path('storage/items_images/webp');

        if (!File::exists($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        $files = File::files($sourceDir);

        $this->info('Found ' . count($files) . ' images');

        $manager = new ImageManager(new Driver());

        foreach ($files as $file) {
            $ext = strtolower($file->getExtension());

            if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                continue;
            }

            $base = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            $webpPath = "{$targetDir}/{$base}.webp";

            if (file_exists($webpPath)) {
                $this->line("Skipped: {$base}");
                continue;
            }

            $image = $manager->read($file->getRealPath());
            $image->scale(width: 1400);
            $image->toWebp(quality: 88)->save($webpPath);

            $this->line("Generated: {$base}.webp");
        }

        $this->info('WebP generation completed.');
    }
}
